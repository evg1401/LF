# lightFramework

PHP 8 <=

## Роутинг

В Routes/routes.php находятся все маршруты приложения. Маршрут должен быть вида:

'home' => ['controller' => 'indexController', 'action' => 'index'], где 'home' - строка запроса, которая может иметь вид,
'home/login' или 'Login/App/ACTION' и т.д. В таком случае полная строка запроса с адресом сайта будет иметь вид - http://my-site.com/home или  http://my-site.com/home/login.
В данном массиве название ключей 'controller' и 'action' изменять нельзя, иначе маршрут не сработает. Название контроллера и экшена может быть любым алфавитно-цифровым значением латиницей. Регистр имеет значение. Если нужно закрыть маршрут от незарегистрированных посетителей, добавьте в маршут значение Auth следующим образом:
'home' => ['controller' => 'indexController', 'action' => 'index', 'Auth']; в данном случае маршрут home станет доступен только авторизованным посетителям.

## Контроллер

В App/Controllers находятся все контроллеры приложения.
Контроллеры находятся в пространстве имен namespace App\Controllers;
В нем, при помощи директив use подключаются модели, Request для получения/отправки http-запросов и т.д.
Для отправки HTML-формы укажите в теге action="/home", где home-строка запроса ссылающаяся на метод-обработчик в контроллере.
Выглядеть это должно следующим образом:


<?php

//App/Controllers/controller_name.php

  namespace App\Controllers;

  use App\Models\News; //модель News
  use Core\Controller; //базовый контроллер (обязательно)
  use Core\Http\Request; //http-клиент для получения/отправки HTTP-запросов

  class indexController extends Controller

    {
      public function post($id, $category, $post)
      {
        $this->render('post.html.twig', compact('id', 'category', 'post')); /* метод render подключает файл с представлением, первым аргументом отправляет название файла представления с расширением (php, html, html.twig и т.д.), (или category/category.php) относительно дирректории App/Views; вторым аргументом передает массив с данными, которые нужно отправить в представление. */
      }

      public function news($id)
      {
        $news = new News(); //создаем объект модели
        $text = $news->readNews($id);
        //$news->writeNews($text);
        //$news->create();
        $this->render('news.html.twig', compact('text'));
      }
      public function getForm() {
        $request = new Request();
        $form = $request->getPost('text');
        $this->render('form.html.twig);
      }
    }

## Модель

В App/Models в находятся модели. При помощи моделей осуществляется взаимодействие с БД

  <?php

  namespace App\Models;

  use RedBeanPHP\R;
  use RedBeanPHP\SimpleModel;

  class News extends SimpleModel
  {
    public function writeNews($text)
    {


        //запись в таблицу

        $news = R::dispense("news");
        $news->text = $text;
        R::store($news);

    }

    public function readNews($id)
    {

        return R::load('news', $id);

    }

    public function create()
    {
        $cars = R::dispense('cars'); //передаем название таблицы cars

        $cars->mercedes = $data['mercedes'];
        $cars->lada = $data['lada'];

        R::store($cars); // сохраняем объект с данными в таблице
    }
  }

Все классы модели должны наследоваться от базового класса RedBeanPHP\SimpleModel. Для работы с базой данных используется RedBeanPHP ORM. https://redbeanphp.com.
Настройки для подключения к БД в корневом каталоге в файле config.ini.

## View

Все файлы предстваления должны находится в App/Views, страницы с кодами ошибок в каталоге App/Views/Errors.

## Request/Response. Получение данных пользовательского ввода и отправка ответов.

Для получения данных с html-форм в контроллере необходимо подключить класс Core\Http\Request.
Все данные получаемые через класс Core\Http\Request фильтруются от вредоносного кода.

### Для получения GET-запросов:

$request = new Core\Http\Request;
$var = $request->getQuery('var');
var_dump($var);

### Для получения POST-запросов:

$request = new Core\Http\Request;
$id = $request->getPost('id');
var_dump($id);

### Для получения файла из HTML-формы:

$file = $request->getFiles('file');
param (string) $file
return mixed

### Для получения COOKIE:

getCookie('$key');
param string
return mixed

### Анализ входящих данных с HTTP-запросом:

getParsedData($key)
Если запрос не пустой, вернет значение.
param string
return mixed

### Отправка HTTP-ответа:

$config = [
	'code'    => 200,
	'headers' => [
		'Content-Type' => 'text/html'
	]
];

$response = new Core\Http\Response($config);
$response->setBody('Какой-то там текст.');
$response->send();

### Проверка типа запроса.

  if ($request->isPost()) {
    //
  }

Также для этих целей предусмотрены методы для проверки:
  hasFiles(), для проверки на наличие файлов в запросе:
  isGet(), проверка на тип GET;
  isHead(), проверка на тип HEAD;
  isPut();
  isDelete();
  isTrace();
  isOptions();
  isConnect();
  isSecure(), для возврата значения соединения независимо от того является ли запрос безопасным;
  isPatch();
  isXhr().
  Общий метод для проверки типов HTTP-запросов - isMethod($method), в качестве аргумента принимает строку: 'GET', 'POST', 'HEAD', 'PUT', 'DELETE', 'TRACE', 'OPTIONS', 'CONNECT', 'SECURE', 'PATH', 'XHR'.


### Использование CURL:

$request = $request->curl("https://example.com/account/login/action","POST");
 //Установка заголовков передачи/возврата
$request->setReturnHeader(true)->setReturnTransfer(true);
//Установить поля ввода
$request->setFields([
	'username'  => 'your-username',
	'password' => 'your-password'
]);
//Отправка...
$request->send();
// Ответ => 200
$statusCode = $request->getCode();
//Распечатка HTTP-ответа
echo "<br\>".$request->getBody();
