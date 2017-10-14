<html>
	<head>
		<title>Жира-поможира: новые задачи на доске</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	</head>
    <body>
    <?php

    require_once 'vendor/autoload.php';
    require_once 'lib/NewIssueDetector/NewIssuesDetector.class.php';
	require_once 'lib/NewIssueDetector/IssueStorage.class.php';
	require_once 'lib/Helper.class.php';

	$newIssuesDetector = new NewIssuesDetector();

	if (isset($_GET['save']))
    {
        $newIssues = explode(',', $_GET['issues']);
        $newIssuesDetector->save($newIssues);
        die('Задачи сохранены');
    }

	$latestDate = IssueStorage::getMaxDate();
	$newIssues = $newIssuesDetector->detectNewIssues();
	echo "Новые задачи " . (!is_null($latestDate) ? "после $latestDate" : '') . ':<br>';
    foreach ($newIssues as $i)
        echo $i . '<br>';
    ?>
    <a href="print_issues.php?issues=<?=implode(',', $newIssues)?>" target="_blank">Напечатать задачи</a><br>
    <a href="new_issues_on_board.php?save=1&issues=<?=implode(',', $newIssues)?>">Сохранить</a>
	</body>
</html>
