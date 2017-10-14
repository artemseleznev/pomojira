<?php
/**
 * @author Seleznyov Artyom seleznev@tutu.ru
 */

class IssueStorage
{
	const TABLE_NAME = 'cf_board_issues';

	public static function insert(array $issues)
	{
		foreach ($issues as $i)
		{
			$sth = self::_PDO()->prepare('INSERT INTO '.self::TABLE_NAME.' (issue_key, `date`) VALUES(:issueKey, NOW())');
			$sth->execute(['issueKey' => $i]);
		}
	}

	public static function select(array $where = []): array
	{
		return self::_PDO()->query('select * from ' . self::TABLE_NAME)->fetchAll();
	}

	public static function update($id, array $fields = [])
	{
		$sth = self::_PDO()->prepare('UPDATE '.self::TABLE_NAME.' SET `date` = NOW() WHERE issue_key = :issueKey');
		$sth->execute(['issueKey' => $id]);
	}

	public static function getMaxDate()
	{
		$result = self::_PDO()->query('select max(`date`) as date from '.self::TABLE_NAME)->fetch();
		return isset($result['date']) ? $result['date'] : null;
	}

	private static function _PDO()
	{
		return new PDO('mysql:dbname=pomojira;host=localhost', 'root', '');
	}
}