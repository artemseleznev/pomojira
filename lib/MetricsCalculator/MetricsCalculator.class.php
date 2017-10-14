<?php
/**
 * @author Seleznyov Artyom seleznev@tutu.ru
 */

use chobie\Jira\Issues\Walker;
use chobie\Jira\Issue;

class MetricsCalculator
{
	public function calculate(): array
	{
		// пока только основной поток
		$issueList = $this->_getIssuesIterator();
		$result = [];

		foreach ($issueList as $issue)
		{
			$planningDateTime = $this->_getPlanningDateTime($issue);
			$workStartDateTime = $this->_getWorkStartDateTime($issue);
			$resolvedDateTime = (new DateTime($issue->get('Resolved')))->format('Y-m-d H:i:s');

			$result[$issue->getKey()] = [
				'component' => $this->_getComponent($issue),
				'planningDateTime' => $planningDateTime,
				'workStartDateTime' => $workStartDateTime,
				'resolvedDateTime' => $resolvedDateTime,
				'inProgressTime' => round($this->_calcLeadTimeInDays($workStartDateTime, $resolvedDateTime), 2),
				'leadTime' => round($this->_calcLeadTimeInDays($planningDateTime, $resolvedDateTime), 2)
			];
		}
		//var_dump($result);
		uasort($result, function($a, $b) { return ($a['resolvedDateTime'] < $b['resolvedDateTime']) ? -1 : 1; });

		return $result;
	}

	private function _getIssuesIterator()
	{
		$walker = new Walker(Helper::getApi());
		$walker->push(
			"project = 'Кросс-функциональная команда' 
			AND status in (resolved, closed) 
			AND issuetype not in (QA-Dev, QA-Task) 
			AND NOT component in (ЛК, ПЛ, Фронтэнд, Главная) 
			AND 'Дата планирования' is not EMPTY 
			Order BY Rank ASC"
		);

		return $walker;
	}

	private function _getComponent(Issue $issue): string
	{
		return $issue->get('Component/s')[0]['name'];
	}

	private function _getPlanningDateTime(Issue $issue): string
	{
		$planningDate = $issue->get('Дата планирования');
		$planningTimeInterval = new DateInterval('PT15H');
		return (new DateTime($planningDate))->add($planningTimeInterval)->format('Y-m-d H:i:s');
	}

	private function _getWorkStartDateTime(Issue $issue): string
	{
		$history = Helper::getApi()->getIssue($issue->getKey(), 'changelog')->getResult()['changelog']['histories'];
		$inToDoActions = [];
		foreach ($history as $action)
		{
		    $actionData = $action['items'][0];
			if ($actionData['fromString'] == 'Open' && $actionData['toString'] == 'To Do')
			{
				$inToDoActions[] = $action['created'];
			}
		}
		rsort($inToDoActions);

		return (new Datetime($inToDoActions[0]))->format('Y-m-d H:i:s');
	}

	private function _calcLeadTimeInDays(string $planningDateTime, string $resolvedDateTime): float
	{
		$planningDateTime = (new DateTime($planningDateTime));
		$resolvedDateTime = (new DateTime($resolvedDateTime));
		$diffInterval = $planningDateTime->diff($resolvedDateTime);
		return $diffInterval->days + $diffInterval->h / 24;
	}
}