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

    // url prefix
    public $url_prefix = 'https://en.wikipedia.org/wiki/';

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
     * sparql query
     * 
     */
    public function querySparql($query_str) {
        $arc = \ARC2::getRemoteStore($this->dbpedia);
        $res = $arc->query($query_str, 'rows');

        // if( ($res) && (isset($res['result'])) && (isset($res['result']['rows'])) ) {
        //     $rows = $res['result']['rows'];
        // } else {
        //     $rows = [];
        // }
        return $res;
    }


    /**
     * 判断语言
     * 
     */
    public function getLang($keyword) {
        // 判断语言
        if(preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $keyword)>0) {  
            $lang = 'zh'; 
        } else if(preg_match('/[\x{4e00}-\x{9fa5}]/u', $keyword)>0) {  
            $lang = 'zh';
        } else {
            $lang = 'en';
        }
        return $lang;
    }


    /**
     * 搜索出版物
     * 
     */
    public function getPubs($keyword, $type) {
        $lang = $this->getLang($keyword);

        $query_str = <<<SPARQL
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
LIMIT 20
SPARQL;
        // echo $query_str;die;
        $rows = $this->querySparql($query_str);
        return $rows;
    }

    /**
     * 搜索出版物
     * 
     */
    public function actionSearch() {
        $keyword = trim(Yii::$app->request->post('keyword'));
        $pub_types = $this->pub_types;
        $data = [];
        $nums = [];
        $is_plot = 0;

        if(empty($keyword))
            $this->redirect(Url::toRoute('site/index'));

        foreach($pub_types as $type) {
            $rows = $this->getPubs($keyword, $type);

            if(!empty($rows))
                $is_plot = 1;

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
            'lang' => $this->getLang($keyword),
        ]);
    }

    /**
     * 查询出版物详细信息
     * 
     */
    public function queryInfo($pid, $type='Film', $lang='en') {
        $arc = \ARC2::getRemoteStore($this->dbpedia);
        $query_str = <<<SPARQL
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX dbo: <http://dbpedia.org/ontology/>

SELECT *
WHERE {
?uri rdf:type dbo:$type ;
     dbo:wikiPageRevisionID ?pid ;
     rdfs:label ?name ;
     dbo:abstract ?abstract .
     FILTER ( (?pid=$pid)&&(lang(?name)='$lang')&&(lang(?abstract)='$lang') )
}
LIMIT 1
SPARQL;
        

        $rows = $this->querySparql($query_str);
        if(!empty($rows))
            $rows = $rows[0];
        // print_r($query_str);die;
        return $rows;
    }



    /**
     * 出版物详细页面
     * 
     */
    public function actionInfo() {
        $pid = intval(Yii::$app->request->get('pid', 1));
        $type = Yii::$app->request->get('type', 'Film');
        $lang = Yii::$app->request->get('lang', 'en');

        if(!in_array($type, $this->pub_types))
            $this->redirect(Url::toRoute('site/index'));

        if(!in_array($lang, ['zh', 'en'])) 
            $lang = 'en';

        $info = $this->queryInfo($pid, $type, $lang);

        if(isset($info['uri']))
            $info['uri'] = $this->url_prefix . basename($info['uri']);

        if(!empty($info) && isset($info['name'])) {
            $pubname = $info['name'];
            // 推荐
            $name = basename($info['uri']);
            // echo $name;die;
            $data = $this->recommend($name);
            print_r($data);die;
        } else {
            $pubname = '没有相关信息';
        }
        
        $title = '相似' . $this->labels[$type];

        return $this->render('info', [
            'pubname' => $pubname,
            'type' => $type,
            'info' => $info,
            'title' => $title,
        ]);
    }



    /**
     * 相似出版物推荐
     * 
     */
    public function recommend($name) {

        $cmd = "python D:/xampps/htdocs/frrs/python/sparql.py $name";
        $json = '';

        $handle = popen($cmd, 'r');
        while($rows = fread($handle, 1024))
            $json .= $rows;
        pclose($handle);

        $data = json_decode($json, true);
        return $data;
    }
}
