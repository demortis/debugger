<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 05.09.18
 * Time: 15:13
 */

namespace debugger\modules\sql\modules\query;

use debugger\DebugModule;
use debugger\modules\sql\exceptions\SqlExplainObjectNotFoundException;
use debugger\modules\sql\exceptions\SqlParserObjectNotFoundException;

/**
 * Class SqlQueryDebug
 * @package debugger\modules\sql\core
 */
class SqlQueryDebug extends DebugModule
{
    /**
     * Кол-во символов после запятой во времени измерения исполнения sql запроса
     */
    const TIME_DECIMALS = 5;

    /**
     * Объект формирующий explain sql запроса
     * @var SqlQueryExplain
     */
    private $explain;

    /**
     * Объект парсер sql запроса
     * @var SqlQueryParser
     */
    private $parser;

    /**
     * Актуальное подключение используемое для запроса
     * @var mixed
     */
    private $connection;

    /**
     * Результат отработки запроса
     * @var mixed
     */
    public $result;

    /**
     * Строка sql запроса
     * @var string
     */
    public $query_string;

    /**
     * Тип sql запроса
     * @var string
     */
    public $type;

    /**
     * Таблицы учавствующие в запросе
     * @var string
     */
    public $tables;

    /**
     * Колонки участвующие в запросе
     * @var string
     */
    public $columns;

    /**
     * Условия запроса where
     * @var string
     */
    public $condition;

    /**
     * Время исполнения запроса в мс.
     * @var double
     */
    public $execution_time;

    private $exception;


    /**
     * SqlQueryDebug constructor.
     * @param $connection
     * @param $query
     */
    public function __construct($connection, $query)
    {
        $this->query_string = $query;
        $this->connection = $connection;
    }

    /**
     * Метод измеряет время исполнения sql запроса, одновременно сохраняя его результат в $this->result
     */
    private function measureExecutionTime(): void  // TODO сделать обработку ошибок
    {
        $startTime = microtime(true);
        $this->result = $this->connection->query($this->query_string);
        $endTime = microtime(true);

        $this->execution_time = number_format($endTime - $startTime, self::TIME_DECIMALS);
    }

    /**
     * Сеттер объекта парсера sql запроса
     * @param SqlQueryParser $parser
     */
    public function setSqlQueryParser(SqlQueryParser $parser): void
    {
        $this->parser = $parser;
    }

    /**
     * Геттер объекта парсера sql запроса
     * @return mixed
     * @throws SqlParserObjectNotFoundException объект парсер не установлен
     */
    public function getSqlQueryParser(): SqlQueryParser
    {
        if (is_null($this->parser)) {
            throw new SqlParserObjectNotFoundException();
        }

        return $this->parser;
    }

    /**
     * Геттер объекта формирующего explain запроса
     * @return mixed
     * @throws SqlExplainObjectNotFoundException объект explain не установлен
     */
    public function getSqlQueryExplain(): SqlQueryExplain
    {
        if (is_null($this->explain)) {
            throw new SqlExplainObjectNotFoundException();
        }

        return $this->parser;
    }

    /**
     * Сеттер объекта формирующего explain запроса
     * @param SqlQueryExplain $explain
     */
    public function setSqlQueryExplain(SqlQueryExplain $explain): void
    {
        $this->explain = $explain;
    }

    /**
     * @return mixed
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @param mixed $exception
     */
    public function setException($exception): void
    {
        $this->exception = $exception;
    }

    /**
     * Метод запускает парсер запроса
     */
    public function parse(): void
    {
        $parser = $this->getSqlQueryParser();
        $parser->setQuery($this->query_string);
        $parser->parse();
//
//        foreach ($queryParts as $type => $value) {     // TODO свойства оставить в парсере и забирать из парсера
//            if (property_exists($this, $type)) {
//                $this->$type = $value;
//            }
//        }
    }

    /**
     * Метод возвращает результат запроса
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Метод запускающий все парсеры //TODO нужно подумать
     */
    public function debug(): void
    {
        $this->measureExecutionTime();
        $this->parse();
    }
}