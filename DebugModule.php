<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 11.09.18
 * Time: 9:00
 */

namespace debugger;

use debugger\core\DebugObject;
use debugger\core\exceptions\ViewNotFoundException;
use debugger\core\interfaces\Module;
use debugger\core\ModuleCollection;

/**
 * Class DebugModule
 * Абстрактный класс подключаемого к дебаггеру модуля.
 * Все модули должны наследоваться от него.
 * Все модули создаются как singleton по lsp.
 * @package debugger\core\interfaces
 *
 * TODO может singleton не самая лучшая идея.
 */
class DebugModule extends DebugObject implements Module
{
    /**
     * Имя папки с файлами отображения
     */
    const VIEW_FOLDER = 'view';

    /**
     * Имя папки с файлами стилей, скриптов
     */
    const ASSETS_FOLDER = 'assets';

    /**
     * Путь по умолчанию к конфигурационному файлу
     */
    const CONFIG_PATH = '/config/config.php';

    /**
     * Singleton instance объекта модуля
     * @var DebugModule
     */
    protected static $instance;

    /**
     * Массив подключаемых модулей.
     * Подмодули описываются в config.php файле.
     * @var ModuleCollection
     */
    public $submodules = [];


    public $name;

    /**
     * Массив исключений которые выкидывают модули при инициализации
     * @var \Exception[]
     */
    public $exceptions = [];

    protected $parent;

    /**
     * Метод возвращает объект модуля (singleton).
     * Консрукция объекта и его реконструкция идут по следующему алгоритму
     *
     * 1. Если объекта нет
     *    объект создается и конфигурируется.
     * 2. Если объект есть и get вызван с пустым параметром $properties
     *    объект возвращается без конфигурации
     * 3. Если параметр $properties не пустой
     *    объект создается либо возвращается предварительно сконфигурированным
     *
     * @param array $properties
     * @return DebugModule
     */
    public static function get(array $properties = []): self
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        } elseif (empty($properties)) {
            return self::$instance;
        }

        $properties = $properties ? current($properties) : [];
        $properties = array_merge(static::$instance->getConfig(), $properties);

        DebugObject::configure(static::$instance, $properties);

        return static::$instance;
    }

    /**
     * Закрываем конструктор.
     */
    protected function __construct()
    {
    }

    /**
     * Закрываем клонирование.
     */
    protected function __clone()
    {
    }

    public function getName(): string
    {
        if (is_null($this->name)) {
            return get_class($this);
        }

        return $this->name;
    }

    public function getParent(): DebugModule
    {
        return $this->parent;
    }

    /**
     * Метод добавляет исключение возникшее при иницализации подмодуля в массив
     * @param string $module_name
     * @param \Exception $exception
     */
    public function addException(string $module_name, \Throwable $exception): void
    {
        $this->exceptions[$module_name][] = $exception;
    }

    /**
     * Метод возвращает массив исключений возникших при инициализации подмодулей
     * @return \Exception[]
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }

    /**
     * Метод возвращает массив настроек из файла config.php, либо [], если таковой отсутствует.
     * Файл массива настроек пока не обязателен.
     * @return array
     */
    public function getConfig(): array
    {
        $config = @include $this->getModuleRootPath(true) . self::CONFIG_PATH;
        return $config ?: [];
    }

    /**
     * Метод возвращает относительный корневой путь модуля.
     * @param boolean $is_absolute true от корня фс, false от корня сайта
     * @return string
     */
    public function getModuleRootPath(bool $is_absolute = false): string
    {
        $class_path = str_replace('\\', DIRECTORY_SEPARATOR, static::class);
        $root_path = dirname(DIRECTORY_SEPARATOR . $class_path);
        return (!$is_absolute ? '' : Autoloader::getRoot()) . $root_path;
    }

    /**
     * Метод возвращает относительный путь к папаке assets модуля.
     * @return string
     */
    public function getAssetsPath(): string
    {
        $root_path = $this->getModuleRootPath();
        return $root_path . DIRECTORY_SEPARATOR . self::ASSETS_FOLDER;  //TODO убрать какаху 'https://4.kolesa-darom.ru' .

    }

    /**
     * Метод возвращает полный путь до папки с view файлами
     * @return string
     */
    public function getViewPath(): string
    {
        $root_path = $this->getModuleRootPath(true);
        return $root_path . DIRECTORY_SEPARATOR . self::VIEW_FOLDER;
    }

    /**
     * Метод возвращает строку html для отображения ее в панели.
     * @return string
     * @throws ViewNotFoundException
     */
    public static function getPanel(): string
    {
        $self = static::get();

        if (!file_exists($view = $self->getViewPath() . '/index.php')) {
            throw new ViewNotFoundException();
        }

        ob_start();

        ob_implicit_flush(false);

        $vars = array_merge(get_object_vars($self), ['module' => $self]);

        extract($vars);

        include_once $view;

        $panel = ob_get_contents();

        ob_end_clean();

        return $panel;
    }
}