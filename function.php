<?php
    if (isset($_POST['check'])){
        if (empty($_POST['url'])){
            echo 'Empty form !!!';
        } else {
            $url = checkUrl(trim($_POST['url']));
                if($url === false){
                    echo 'Invalid url!!!';
                } else {
                    $table = checkRobots($url);
                }
            }
        }
     
        function checkUrl($url) {//check and raplase url
            $regExp = '~^(http://|https://)(www.)?(([a-z0-9]([-a-z0-9]*[a-z0-9]+)?){1,63}\.)+[a-z]{2,6}$~';
            switch ($url){
                
                case preg_match($regExp, $url) && checkResponse($url):
                    return $url.'/robots.txt';
                break;
            
                case preg_match($regExp, 'https://'.$url) && checkResponse('https://'.$url):
                    return 'https://'.$url.'/robots.txt';
                break;
            
                case preg_match($regExp, 'https://www.'.$url) && checkResponse('https://www.'.$url):
                    return 'https://www.'.$url.'/robots.txt';
                break;
                
                case preg_match($regExp, 'http://'.$url) && checkResponse('http://'.$url):
                    return 'http://'.$url.'/robots.txt';
                break;
            
                case preg_match($regExp, 'http://www.'.$url) && checkResponse('http://www.'.$url):
                    return 'http://www.'.$url.'/robots.txt';
                break;
            
            default :
                return false;
            }
        }
        
        function checkResponse($url) {
            $check = get_headers($url.'/robots.txt');
                if (preg_match('~200~', $check[0])){
                    return TRUE;
                } else {
                    return FALSE;
                }
        }
        
        function checkRobots($url) {
        $result = get_headers($url);
        //file exist?
        if (preg_match('~200~', $result[0])){
            $table[]  = ['Проверка наличия файла robots.txt',
                         'Ok',
                         'Файл robots.txt присутствует',
                         'Доработки не требуются'];
            $content = file_get_contents($url, true);
            } else {
            $table[]= ['Проверка наличия файла robots.txt',
                        'Ошибка',
                        'Файл robots.txt отсутствует',
                        'Программист: Создать файл robots.txt и разместить его на сайте.'];
            $content = "";
            }
            //host exist?
                if (preg_match('~Host:|host:~', $content)){
                    $table[]  = ['Проверка указания директивы Host',
                                 'Ok',
                                 'Директива Host указана',
                                 'Доработки не требуются'];
                } else {
                    $table[]  = ['Проверка указания директивы Host',
                                 'Ошибка',
                                 'В файле robots.txt не указана директива Host',
                                 'Программист: Для того, чтобы поисковые системы знали, какая версия сайта является основных зеркалом, необходимо прописать адрес основного зеркала в директиве Host. В данный момент это не прописано. Необходимо добавить в файл robots.txt директиву Host. Директива Host задётся в файле 1 раз, после всех правил.'];
                }
            //host count
                 $host = preg_match_all('~Host:|host:~', $content);
                if ($host == 1){
                    $table[] = ['Проверка количества директив Host, прописанных в файле',
                                'Ok',
                                'В файле прописана 1 директива Host',
                                'Доработки не требуются'];
                } else if ($host > 1){
                    $table[]  = ['Проверка количества директив Host, прописанных в файле',
                                 'Ошибка',
                                 'В файле прописано несколько директив Host',
                                 'Программист: Директива Host должна быть указана в файле толоко 1 раз. Необходимо удалить все дополнительные директивы Host и оставить только 1, корректную и соответствующую основному зеркалу сайта'];
                }
            //file size
                if (strlen($content) < 32000 ){
                    $table[] = ['Проверка размера файла robots.txt',
                               'Ok',
                               'Размер файла robots.txt составляет '.strlen($content).' байта, что находится в пределах допустимой нормы',
                               'Доработки не требуются'];
                } else {
                    $table[] = ['Проверка размера файла robots.txt',
                               'Ошибка',
                               'Размера файла robots.txt составляет '.strlen($content).' байта, что превышает допустимую норму',
                               'Программист: Максимально допустимый размер файла robots.txt составляем 32 кб. Необходимо отредактировть файл robots.txt таким образом, чтобы его размер не превышал 32 Кб'];
                }
            
             //Sitemap
                if (preg_match_all('~Sitemap~', $content)){
                    $table[] = ['Проверка указания директивы Sitemap',
                               'Ok',
                               'Директива Sitemap указана',
                               'Доработки не требуются'];
                } else {
                    $table[] = ['Проверка указания директивы Sitemap',
                               'Ошибка',
                               'В файле robots.txt не указана директива Sitemap',
                               'Программист: Добавить в файл robots.txt директиву Sitemap'];
                }
            //Response
                if (preg_match('~200~', $result[0])){
                        $table[] = ['Проверка кода ответа сервера для файла robots.txt',
                                   'Ok',
                                   'Файл robots.txt отдаёт код ответа сервера 200',
                                   'Доработки не требуются'];
                    } else {
                        $table[] = ['Проверка кода ответа сервера для файла robots.txt',
                                   'Ошибка',
                                   'При обращении к файлу robots.txt сервер возвращает код ответа: '.$result[0],
                                   'Программист: Файл robots.txt должны отдавать код ответа 200, иначе файл не будет обрабатываться. Необходимо настроить сайт таким образом, чтобы при обращении к файлу robots.txt сервер возвращает код ответа 200'];
                        }
            return $table;
    }


    