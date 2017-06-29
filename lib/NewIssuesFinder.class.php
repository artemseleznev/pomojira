<?php
/**
 * @author Seleznyov Artyom seleznev@tutu.ru
 */

use chobie\Jira\Issues\Walker;

class NewIssuesFinder
{
	public function findNewIssues()
	{
		$issueList = $this->_getIssuesFromBoard();
		$savedIssuesList = $this->_getSavedIssues();
		$newIssues = [];
		foreach ($issueList as $i)
		{
			if (!in_array($i, $savedIssuesList))
			{
				$newIssues[] = $i;
			}
		}

		return $newIssues;
	}

	public function save($issueList)
	{
		$savedIssuesList = $this->_getSavedIssues();
		foreach ($issueList as $i)
		{
			if (!in_array($i, $savedIssuesList))
			{
				IssueStorage::insert([$i]);
				$newIssues[] = $i;
			}
			else
			{
				IssueStorage::update($i);
			}
		}
	}

	private function _getIssuesFromBoard()
	{
		$walker = new Walker(Helper::getApi());
		$walker->push(
			"project = 'CF' 
			AND (status != closed OR resolution = Fixed) 
			AND issuetype not in (epic)
			AND status != Open
			AND (fixVersion in unreleasedVersions() OR fixVersion is EMPTY) and 
			(
				issuetype not in subTaskIssueTypes() or 
				(
					status not in (resolved, closed, 'To Do')
				)
			)"
		);
		$issueList = [];
		foreach ($walker as $issue) {
			$issueList[] = $issue->getKey();
		}

		return $issueList;
	}

	private function _getSavedIssues()
	{
		$res = [];
		foreach (IssueStorage::select() as $item)
		{
			$res[] = $item['issue_key'];
		}
		return $res;
	}
}