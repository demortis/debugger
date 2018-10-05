<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 06.09.18
 * Time: 8:41
 */

namespace debugger\modules\sql\modules\query\modules\parser;

use debugger\DebugModule;
use debugger\modules\sql\exceptions\NotDefinedRuleException;
use debugger\modules\sql\exceptions\UndefinedQueryTypeException;

/**
 * Класс парсера строки sql запроса.
 * Class SqlQueryParser
 * @package debugger\modules\sql\core
 *
 * TODO не готов. Нужно добавить правил и дополнить имеющиеся.
 */
class SqlQueryParser extends DebugModule
{
    /**
     * Строка sql запроса
     * @var
     */
    private $query;

    /**
     * Массив правил. По сути регулярныу выражения.
     * TODO перенести в config.php
     * @var array
     */
    private $rules = [
        'select' =>  "/^(?'type'SELECT){1}\s(?'param_0'ALL|DISTINCT|DISTINCTROW)?\s?(?'param_1'STRAIGHT_JOIN)?\s?(?'cache'SQL_NO_CACHE)?\s?(?'columns'.+) FROM (?'tables'.+){1}/i",
        'insert' => '',
        'update' => "/^(?'type'UPDATE){1}\s(?'tables'.+){1} SET (?'columns'.+){1}/",
        'replace' => '',
//        'set' => '',
        'delete' => ''
    ];

    private $query_type;

    /**
     * $query setter
     * @param string $query
     */
    public function setQuery(string $query)
    {
        $this->query = $query;
    }

    /**
     * $query getter
     * @return string
     */
    public function getQuery() : string
    {
        return $this->query;
    }

    /**
     * Метод парсит строку sql запроса используя $this->rules, и возвращает массив.
     * @return array
     */
    public function parse() : array
    {
        $result = preg_match_all($this->getQueryParseRule(), $this->getQuery(), $matches, PREG_SET_ORDER);

        if (!$result)
            return [];

        $matches = array_filter(current($matches), function($k) {
            return !is_int($k);
        }, ARRAY_FILTER_USE_KEY);

        return $matches;
    }

    /**
     * Метод определяет тип запроса (select, insert и т.д.)
     * @throws UndefinedQueryTypeException тип sql запроса не зарегистрирован в правилах парсера
     */
    private function defineQueryType(): void
    {
        $query = explode(' ', strtolower($this->getQuery()));
        $types = array_keys($this->rules);
        $query_type = current(array_intersect($types, $query));

        if(!$query_type) {
            $this->query_type = 'undefined';
            throw new UndefinedQueryTypeException($this->getQuery());
        }

        $this->query_type = strtolower($query_type);
    }

    /**
     * $query_type getter
     * Если свойство не определено запускает метод определения
     * @return string
     */
    public function getQueryType(): string
    {
        if(is_null($this->query_type)) {
            $this->defineQueryType();
        }

        return $this->query_type;
    }

    /**
     * Метод возвращает правило парсинга строки запроса по типу запроса.
     * @return string правило парсинга sql запроса. По сути регулярное выражение.
     * @throws NotDefinedRuleException правило парсера не определено по данному типу sql запроса
     */
    public function getQueryParseRule() : string
    {
        $query_type = $this->getQueryType();
        if (empty($this->rules[$query_type]))
            throw new NotDefinedRuleException($query_type);

        return $this->rules[$query_type];
    }
}