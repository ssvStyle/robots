<?php
    //ini_set('display_errors', true);
    //error_reporting(E_ALL);
    if (isset($_POST['check'])){
        if (empty($_POST['url'])){
            echo 'Empty form !!!';
        } else {
            $url = checkUrl(trim($_POST['url']));
            $table = checkRobots($url);
            }
        }
    if (isset($_POST['save']) && !empty($_POST['saveUrl'])) {
                $tableToFile = checkRobots($_POST['saveUrl']);
                saveToFile($tableToFile, $_POST['saveUrl']);
                //echo $result;
                //var_dump($tableToFile);
                //var_dump($result);
            }
            
             /*$curl = curl_init();
             curl_setopt_array($curl, array( CURLOPT_URL => $url, CURLOPT_HEADER => true, CURLOPT_RETURNTRANSFER => true, CURLOPT_NOBODY => true));
             $header = explode("\n", curl_exec($curl));
             curl_close($curl);
             print_r($header);*/
            
            
            
       //var_dump($Excel);
    function saveToFile($table, $url) {
        require_once 'Classes/PHPExcel.php';
            $Excel = new PHPExcel();//обьект класса phpexel
            $Excel->setActiveSheetIndex(0);// указываем индекс активного листа
            $MySheet = $Excel->getActiveSheet();//обьект!!! активного листа
            $MySheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);//settings
            $MySheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
            $MySheet->getPageMargins()->setTop(1);//settings
            $MySheet->getPageMargins()->setRight(0.75);//settings
            $MySheet->getPageMargins()->setLeft(0.75);//settings
            $MySheet->getPageMargins()->setBottom(1);//settings
            $MySheet->setTitle('Robots report');//title
            //set column
            $MySheet->getColumnDimension('A')->setWidth(3);
            $MySheet->getColumnDimension('B')->setWidth(40);
            $MySheet->getColumnDimension('C')->setWidth(10);
            $MySheet->getColumnDimension('D')->setWidth(15);
            $MySheet->getColumnDimension('E')->setWidth(50);
            //add header
            $MySheet->mergeCells('A1:E1');
            $MySheet->setCellValue('A1', $url);
            $MySheet->setCellValue('A2', '№');
            $MySheet->setCellValue('B2', 'Название проверки');
            $MySheet->setCellValue('C2', 'Статус');
            $MySheet->setCellValue('D2', '');
            $MySheet->setCellValue('E2', 'Текущее состояние');
            $MySheet->mergeCells('A1:E1');
            //loop
            $c = 4;
            for($i = 0; $i < count($table); $i++){
                    $MySheet->mergeCellsByColumnAndRow( 0, $c , 0 , ($c+1) );
                    $MySheet->mergeCellsByColumnAndRow(1 , $c , 1 , ($c+1) );
                    $MySheet->mergeCellsByColumnAndRow( 2 , $c , 2 , ($c+1) );
                    $MySheet->setCellValue('A'.$c, ($i+1));
                    $MySheet->setCellValue('B'.$c, $table[$i][0]);
                    $MySheet->setCellValue('C'.$c, $table[$i][1]);
                    $MySheet->setCellValue('D'.$c, 'Состояние');
                    $MySheet->setCellValue('D'.($c+1), 'Рекомендации');
                    $MySheet->setCellValue('E'.$c, $table[$i][2]);
                    $MySheet->setCellValue('E'.($c+1), $table[$i][3]);
                    $c+=2;
            }
            
            $objWriter = PHPExcel_IOFactory::createWriter($Excel, 'Excel5');
            $objWriter->save('report.xlsx');
        
        
        return 'File is saved';
    }
    
    

    function checkRobots($url) {
        $url .= '/robots.txt';
        $result = get_headers($url);
        //echo '<pre>';
        //print_r($result);
        //echo '</pre>';
        if (preg_match('~200~', $result[0])){//file($url.'/robots.txt')  !== false
            //file exist?
            $table[]  = ['Проверка наличия файла robots.txt',
                         'Ok',
                         'Файл robots.txt присутствует',
                         'Доработки не требуются'];
            //host exist?
           $content = file_get_contents($url.'/robots.txt', true);
                if (preg_match_all('~Host:|host:~', $content)){
                    $table[]  = ['Проверка указания директивы Host',
                                 'Ok',
                                 'Директива Host указана',
                                 'Доработки не требуются'];
                    //$table['Host count'] = preg_match_all('~Host:|host:~', $content);
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
            preg_match('~[0-9]{1,}~u', $result[6], $size);
                if ($size[0] < 32000){
                    $table[] = ['Проверка размера файла robots.txt',
                               'Ok',
                               'Размер файла robots.txt составляет '.$size[0].' байта, что находится в пределах допустимой нормы',
                               'Доработки не требуются'];
                } else {
                    $table[] = ['Проверка размера файла robots.txt',
                               'Ошибка',
                               'Размера файла robots.txt составляет '.$size[0].' байта, что превышает допустимую норму',
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
                        }
            return $table;
       } else {
            $table[]= ['Проверка наличия файла robots.txt',
                        'Ошибка',
                        'Файл robots.txt отсутствует',
                        'Программист: Создать файл robots.txt и разместить его на сайте.'];
            $table[] = ['Проверка кода ответа сервера для файла robots.txt',
                                   'Ошибка',
                                   'При обращении к файлу robots.txt сервер возвращает код ответа: '.$result[0],
                                   'Программист: Файл robots.txt должны отдавать код ответа 200, иначе файл не будет обрабатываться. Необходимо настроить сайт таким образом, чтобы при обращении к файлу robots.txt сервер возвращает код ответа 200'];
                    
            return $table;
        }
    }


    function checkUrl($url) {//check and raplase url
        
            if (filter_var($url, FILTER_VALIDATE_URL)){
                return $url;
           } elseif (filter_var('http://'.$url, FILTER_VALIDATE_URL)) {
                return 'http://'.$url; 
                
           } else {
               echo 'Invalid url';
           }
        }