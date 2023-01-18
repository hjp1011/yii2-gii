<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yiiframe\gii\generators\controller;

use Yii;
use yiiframe\gii\CodeFile;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * This generator will generate a controller and one or a few action view files.
 *
 * @property array $actionIDs An array of action IDs entered by the user. This property is read-only.
 * @property string $controllerFile The controller class file path. This property is read-only.
 * @property string $controllerID The controller ID. This property is read-only.
 * @property string $controllerNamespace The namespace of the controller class. This property is read-only.
 * @property string $controllerSubPath The controller sub path. This property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Generator extends \yiiframe\gii\Generator
{
    /**
     * @var string the controller class name
     */
    public $controllerClass;
    /**
     * @var string the controller's view path
     */
    public $viewPath;
    /**
     * @var string the base class of the controller
     */
    public $baseClass = 'yii\web\Controller';
    /**
     * @var string list of action IDs separated by commas or spaces
     */
    public $actions = 'index';


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return Yii::t('app', '控制器生成器');
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return Yii::t('app','这个生成器帮助您快速生成一个新的控制器类，其中包含一个或多个控制器动作及其相应的视图。');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['controllerClass', 'actions', 'baseClass'], 'filter', 'filter' => 'trim'],
            [['controllerClass', 'baseClass'], 'required'],
            ['controllerClass', 'match', 'pattern' => '/^[\w\\\\]*Controller$/', 'message' => 'Only word characters and backslashes are allowed, and the class name must end with "Controller".'],
            ['controllerClass', 'validateNewClass'],
            ['baseClass', 'match', 'pattern' => '/^[\w\\\\]*$/', 'message' => 'Only word characters and backslashes are allowed.'],
            ['actions', 'match', 'pattern' => '/^[a-z][a-z0-9\\-,\\s]*$/', 'message' => 'Only a-z, 0-9, dashes (-), spaces and commas are allowed.'],
            ['viewPath', 'safe'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'baseClass' => Yii::t('app','基类'),
            'controllerClass' => Yii::t('app','控制器类'),
            'viewPath' => Yii::t('app','视图路径'),
            'actions' => Yii::t('app','动作ID'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function requiredTemplates()
    {
        return [
            'controller.php',
            'view.php',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function stickyAttributes()
    {
        return ['baseClass'];
    }

    /**
     * {@inheritdoc}
     */
    public function hints()
    {
        return [
            'controllerClass' => Yii::t('app','这是要生成的控制器类的名称。你应该提供一个完全限定的命名空间类(例如<code>app\controllers\PostController</code>)，并且类名应该在CamelCase中以单词<code>Controller</code>结尾。确保类使用的名称空间与应用程序的controllerNamespace属性指定的名称空间相同。'),
            'actions' => Yii::t('app','提供一个或多个动作id在控制器中生成空的动作方法。用逗号或空格分隔多个动作id。动作id应该小写。例如:<ul><li><code>index</code>生成<code>actionIndex()</code></li><li><code>create-order</code> generated <code>actionCreateOrder()</code></li></ul >'),
            'viewPath' => Yii::t('app','指定存储控制器视图脚本的目录。您可以在这里使用路径别名，例如，<code>/var/www/basic/controllers/views/post</code>,<code>@app/views/post</code>。如果未设置，将默认设置<code>@app/views/ControllerID</code>'),
            'baseClass' => Yii::t('app','这是新控制器类将从其扩展的类。请确保该类存在并且可以自动加载'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function successMessage()
    {
        return 'The controller has been generated successfully.' . $this->getLinkToTry();
    }

    /**
     * This method returns a link to try controller generated
     * @see https://github.com/yiisoft/yii2-gii/issues/182
     * @return string
     * @since 2.0.6
     */
    private function getLinkToTry()
    {
        if (strpos($this->controllerNamespace, Yii::$app->controllerNamespace) !== 0) {
            return '';
        }

        $actions = $this->getActionIDs();
        if (in_array('index', $actions, true)) {
            $route = $this->getControllerSubPath() . $this->getControllerID() . '/index';
        } else {
            $route = $this->getControllerSubPath() . $this->getControllerID() . '/' . reset($actions);
        }
        return ' You may ' . Html::a('try it now', Yii::$app->getUrlManager()->createUrl($route), ['target' => '_blank', 'rel' => 'noopener noreferrer']) . '.';
    }

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        $files = [];

        $files[] = new CodeFile(
            $this->getControllerFile(),
            $this->render('controller.php')
        );

        foreach ($this->getActionIDs() as $action) {
            $files[] = new CodeFile(
                $this->getViewFile($action),
                $this->render('view.php', ['action' => $action])
            );
        }

        return $files;
    }

    /**
     * Normalizes [[actions]] into an array of action IDs.
     * @return array an array of action IDs entered by the user
     */
    public function getActionIDs()
    {
        $actions = array_unique(preg_split('/[\s,]+/', $this->actions, -1, PREG_SPLIT_NO_EMPTY));
        sort($actions);

        return $actions;
    }

    /**
     * @return string the controller class file path
     */
    public function getControllerFile()
    {
        return Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->controllerClass, '\\'))) . '.php';
    }

    /**
     * @return string the controller ID
     */
    public function getControllerID()
    {
        $name = StringHelper::basename($this->controllerClass);
        return Inflector::camel2id(substr($name, 0, strlen($name) - 10));
    }

    /**
     * This method will return sub path for controller if it
     * is located in subdirectory of application controllers dir
     * @see https://github.com/yiisoft/yii2-gii/issues/182
     * @since 2.0.6
     * @return string the controller sub path
     */
    public function getControllerSubPath()
    {
        $subPath = '';
        $controllerNamespace = $this->getControllerNamespace();
        if (strpos($controllerNamespace, Yii::$app->controllerNamespace) === 0) {
            $subPath = substr($controllerNamespace, strlen(Yii::$app->controllerNamespace));
            $subPath = ($subPath !== '') ? str_replace('\\', '/', substr($subPath, 1)) . '/' : '';
        }
        return $subPath;
    }

    /**
     * @param string $action the action ID
     * @return string the action view file path
     */
    public function getViewFile($action)
    {
        if (empty($this->viewPath)) {
            return Yii::getAlias('@app/views/' . $this->getControllerSubPath() . $this->getControllerID() . "/$action.php");
        }

        return Yii::getAlias(str_replace('\\', '/', $this->viewPath) . "/$action.php");
    }

    /**
     * @return string the namespace of the controller class
     */
    public function getControllerNamespace()
    {
        $name = StringHelper::basename($this->controllerClass);
        return ltrim(substr($this->controllerClass, 0, - (strlen($name) + 1)), '\\');
    }
}
