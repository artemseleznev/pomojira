<?php
/**
 * @author Seleznyov Artyom seleznev@tutu.ru
 */

use chobie\Jira\Issues\Walker;

class NewIssuesDetector
{
    public function detectNewIssues()
    {
        $issueList = $this->_getIssuesFromBoard();
        $savedIssuesList = $this->_getSavedIssues();
        $newIssues = [];
        foreach ($issueList as $i) {
            if (!in_array($i, $savedIssuesList)) {
                $newIssues[] = $i;
            }
        }

        return $newIssues;
    }

    public function save($issueList)
    {
        $savedIssuesList = $this->_getSavedIssues();
        foreach ($issueList as $i) {
            if (!in_array($i, $savedIssuesList)) {
                IssueStorage::insert([$i]);
                $newIssues[] = $i;
            } else {
                IssueStorage::update($i);
            }
        }
    }

    private function _getIssuesFromBoard()
    {
        $walker = new Walker(Helper::getApi());
        $walker->push(
            'project = "Кросс-функциональная команда"
			 AND (status != closed OR resolution = Fixed) 
			 AND issuetype != Epic
			 AND status != Open
			 AND (fixVersion in unreleasedVersions() OR fixVersion is EMPTY) AND
 			(
				issuetype not in subTaskIssueTypes() or
				(
					status not in (resolved, closed, "To Do")
				)
 			)'
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
        foreach (IssueStorage::select() as $item) {
            $res[] = $item['issue_key'];
        }
        return $res;
    }
}