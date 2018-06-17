<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{   
    // dbpedia sparql endpoint config
    public $dbpedia = ['remote_store_endpoint' => 'http://dbpedia.org/sparql'];

    // publication types
    public $pub_types = ['Film', 'Book', 'Game'];

    // publication labels
    public $labels = [
        'Film' => '电影',
        'Book' => '图书',
        'Game' => '游戏',
    ];

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }


    /**
     * 搜索电影
     * 
     */
    public function getStr($keyword, $type) {
        // 判断语言
        if(preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $keyword)>0) {  
            $lang = 'zh'; 
        } else if(preg_match('/[\x{4e00}-\x{9fa5}]/u', $keyword)>0) {  
            $lang = 'zh';
        } else {
            $lang = 'en';
        }

        $query_str = <<<EOF
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX dbo: <http://dbpedia.org/ontology/>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>

SELECT distinct ?pub ?name ?abstract ?pid WHERE {
?pub rdf:type dbo:$type ;
     rdfs:label ?name ;
     dbo:abstract ?abstract ;
     dbo:wikiPageRevisionID ?pid .
FILTER ( REGEX(?name, "$keyword", "i") && (LANG(?name)="$lang") && (LANG(?abstract)="$lang") ) 
}
ORDER BY DESC(?pid)
LIMIT 15
EOF;
        return $query_str;
    }

    /**
     * 搜索出版物
     * 
     */
    public function actionSearch() {
        $keyword = trim(Yii::$app->request->post('keyword'));
        $pub_types = $this->pub_types;
        $arc = \ARC2::getRemoteStore($this->dbpedia);
        $url_prefix = 'https://en.wikipedia.org/wiki/';
        $data = [];
        $nums = [];
        $is_plot = 0;

        if(empty($keyword))
            $this->redirect(Url::toRoute('site/index'));

        foreach($pub_types as $type) {
            $query_str = $this->getStr($keyword, $type);
            $res = $arc->query($query_str);

            if( ($res) && (isset($res['result'])) && (isset($res['result']['rows'])) ) {
                $rows = $res['result']['rows'];
            } else {
                $rows = [];
            }

            if(!empty($rows))
                $is_plot = 1;

            // replace wiki url
            /*foreach($rows as $k => $v) {
                $arr = explode('/', $v['pub']);
                $v['pub'] = $url_prefix . end($arr);
                $rows[$k] = $v;
            }*/
            

            $data[$type] = $rows;
            $nums[$type] = count($rows);
        }
        // print_r($data);die;

        return $this->render('result', [
            'keyword' => $keyword,
            'data' => $data,
            'labels' => $this->labels,
            'is_plot' => $is_plot,
            'nums' => $nums,
        ]);
    }

}
