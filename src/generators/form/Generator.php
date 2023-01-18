<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yiiframe\gii\generators\form;

use Yii;
use yii\base\Model;
use yiiframe\gii\CodeFile;

/**
 * This generator will generate an action view file based on the specified model class.
 *
 * @property array $modelAttributes List of safe attributes of [[modelClass]]. This property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Generator extends \yiiframe\gii\Generator
{
    public $modelClass;
    public $viewPath = '@app/views';
    public $viewName;
    public $scenarioName;


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return Yii::t('app','表单生成器');
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return Yii::t('app','此生成器生成一个视图脚本文件，该文件显示用于收集指定模型类输入的表单。');
    }

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        $files = [];
        $files[] = new CodeFile(
            Yii::getAlias($this->viewPath) . '/' . $this->viewName . '.php',
            $this->render('form.php')
        );

        return $files;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['modelClass', 'viewName', 'scenarioName', 'viewPath'], 'filter', 'filter' => 'trim'],
            [['modelClass', 'viewName', 'viewPath'], 'required'],
            [['modelClass'], 'match', 'pattern' => '/^[\w\\\\]*$/', 'message' => 'Only word characters and backslashes are allowed.'],
            [['modelClass'], 'validateClass', 'params' => ['extends' => Model::className()]],
            [['viewName'], 'match', 'pattern' => '/^\w+[\\-\\/\w]*$/', 'message' => 'Only word characters, dashes and slashes are allowed.'],
            [['viewPath'], 'match', 'pattern' => '/^@?\w+[\\-\\/\w]*$/', 'message' => 'Only word characters, dashes, slashes and @ are allowed.'],
            [['viewPath'], 'validateViewPath'],
            [['scenarioName'], 'match', 'pattern' => '/^[\w\\-]+$/', 'message' => 'Only word characters and dashes are allowed.'],
            [['enableI18N'], 'boolean'],
            [['messageCategory'], 'validateMessageCategory', 'skipOnEmpty' => false],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'modelClass' => Yii::t('app','模型类'),
            'viewName' => Yii::t('app','视图名称'),
            'viewPath' => Yii::t('app','视图路径'),
            'scenarioName' => Yii::t('app','场景'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function requiredTemplates()
    {
        return ['form.php', 'action.php'];
    }

    /**
     * {@inheritdoc}
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['viewPath', 'scenarioName']);
    }

    /**
     * {@inheritdoc}
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'modelClass' => Yii::t('app','这是用于收集表单输入的模型类。你应该提供一个完全限定的类名，例如，<code>app\models\Post</code>。'),
            'viewName' => Yii::t('app','这是关于视图路径的视图名。例如，<code>site/index</code>将在视图路径下生成一个<code>site/index.php</code>视图文件。'),
            'viewPath' => Yii::t('app','这是根视图路径，用于保存生成的视图文件。你可以提供一个目录或路径别名，例如，<code>@app/views</code>。'),
            'scenarioName' => Yii::t('app','这是模型在收集表单输入时使用的场景。如果为空，则使用默认场景。'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function successMessage()
    {
        $code = highlight_string($this->render('action.php'), true);

        return <<<EOD
<p>The form has been generated successfully.</p>
<p>You may add the following code in an appropriate controller class to invoke the view:</p>
<pre>$code</pre>
EOD;
    }

    /**
     * Validates [[viewPath]] to make sure it is a valid path or path alias and exists.
     */
    public function validateViewPath()
    {
        $path = Yii::getAlias($this->viewPath, false);
        if ($path === false || !is_dir($path)) {
            $this->addError('viewPath', 'View path does not exist.');
        }
    }

    /**
     * @return array list of safe attributes of [[modelClass]]
     */
    public function getModelAttributes()
    {
        /* @var $model Model */
        $model = new $this->modelClass();
        if (!empty($this->scenarioName)) {
            $model->setScenario($this->scenarioName);
        }

        return $model->safeAttributes();
    }
}
