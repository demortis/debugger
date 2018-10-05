<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 11.09.18
 * Time: 9:08
 */

namespace debugger\modules\sql;

use debugger\core\interfaces\Executive;
use debugger\DebugModule;

/**
 * Класс инициализации sql дебаггера
 * Class SqlDebugModule
 * @package debugger\modules\sql
 */
class SqlDebugModule extends DebugModule implements Executive
{
    /**
     * Экземпляр модуля
     * @var DebugModule
     */
    protected static $instance;
    /**
     * Последний sql запрос отправленный в дебаггер
     * @var string
     */
    protected $query;

    /**
     * Последнее подключение отправленное в дебаггер
     * через которое будет отправлен запрос
     * @var mixed
     */
    protected $connection;

    /**
     * Массив объектов SqlQueryDebug. Распарсенных запросов.
     * @var array
     */
    public $queries = [];

    /**
     * Массив счетчиков запросов, всех и по типу.
     * @var array
     */
    public $counters = [
        'all' => 0
    ];

    /**
     * Результат последнего sql запроса отправленного в дебаггер
     * @var mixed
     */
    private $result;

    /**
     * $query setter
     * @param mixed $query
     */
    public function setQuery(string $query)
    {
        $this->query = $query;
    }

    /**
     * $connection setter
     * @param mixed $connection PDO, mysqli, mysql  //TODO сделать оболочки для всех. А то пока только mysqli
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    /**
     * $result setter
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * $query getter
     * @return mixed
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * $connection $getter
     * @return mixed
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * $result getter
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Метод запускает все существующие анализаторы запроса.
     * @return mixed
     */
    public function execute()
    {

//        $queryModule = $this->submodules->getModuleByName('parser');

        var_dump($this->submodules->getParent());die;



//        $sqlQueryDebug = new SqlQueryDebug($this->getConnection(), $this->getQuery());
//
//        try {
//            $sqlQueryDebug->setSqlQueryParser(new SqlQueryParser());                        // TODO композицию через модули (паттерн композит?)*
//            $sqlQueryDebug->setSqlQueryExplain(new SqlQueryExplain());
//            $sqlQueryDebug->debug();
//        } catch (\Exception $e) {
//            $sqlQueryDebug->setException($e);
//        }
//
//        $this->addQuery($sqlQueryDebug);
//        return $sqlQueryDebug->getResult();
    }

    /**
     * Метод добавляет отдебаженный объект sql запроса в общий реестр
     * @param SqlQueryDebug $query
     */
    public function addQuery(SqlQueryDebug $query): void
    {
        $this->queries[] = $query;
        $this->setCounters($query);
    }

    /**
     * Метод инкременирует общий счетчик запросов + счетчик определенного типа(select, update и т.д.) запросов.
     * Также создает счетчик по типу запроса если его нет.
     * @param SqlQueryDebug $query
     */
    private function setCounters(SqlQueryDebug $query): void
    {
        $this->counters['all']++;
        $type = $query->getSqlQueryParser()->getQueryType();

        if (!isset($this->counters[$type])) {
            $this->counters[$type] = 0;
        }

        $this->counters[$type]++;
    }

    /**
     * Метод возвращает все счетчики запросов
     * @return array
     */
    public function getCounters(): array
    {
        return $this->counters;
    }
}