<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style type="text/css">
            .block{
                width: 500px;
                text-align: center;
                margin: auto;
            }
            .result{
                border: 1px solid black;
                width: 500px;
                text-align: center;
                margin: auto;
            }
            table{
                text-align: center;
                margin: auto;
                padding: 0px;
            }
            .nomber {
                width: 50px;
                border: 1px solid black;
            }
            .CheckName {
                width: 450px;
                border: 1px solid black;
            }
            .status {
                width: 100px;
                border: 1px solid black;
            }
            .state {
                width: 400px;
                border: 1px solid black;
            }
            .empty {
                width: 150px;
                border: 1px solid black;
            }
            .head{
                background-color: #cccccc;
            }
            .statusErr {
                background-color: #e58080;
            }
            .statusOk {
                background-color: #95e580;
            }
            .emptyString {
                height: 10px;
            }
            .save {
                margin: auto;
            }
        </style>
    </head>
    <body>
        <div class="block">
            <?php require ('function.php'); ?>
            <h3>Check robots.txt</h3>
            <form method="post">
                  <p><input type="text" title="" name="url"></p>
                  <input type="submit" name="check" value="check">
                      <?php if (isset($table)):?>
                  <input type="hidden" name="saveUrl" value="<?php echo $url;?>">
                  <input type="submit" name="save" value="Save">
                      <?php endif;?>
            </form>
            <br>
            <?php echo isset($url) ? $url : false;?>
        </div>
        <br>
        <?php if (isset($table)):?>
        <br>
        <table  border="1px" cellpadding="0" cellspacing="0">
            <tr class="head">
                <td class="nomber">№</td>
                <td class="CheckName">Название проверки</td>
                <td class="status">Статус</td>
                <td class="empty"></td>
                <td class="state">Текущее состояние</td>
            </tr>
            <?php for($i=0; $i < count($table); $i++ ):?>
   <tr><td colspan="5"  class="emptyString"></td></tr><!--empty string-->
            
            <tr>
                <td class="nomber" rowspan="2"><?php echo $i+1;?></td>
                <td class="CheckName" rowspan="2"><?php echo $table[$i][0];?></td>
                    <?php if ($table[$i][1] == "Ok"){?>
                <td class="statusOk" rowspan="2"><?php echo $table[$i][1];?></td>
                    <?php } else {;?>
                <td class="statusErr" rowspan="2"><?php echo $table[$i][1];?></td>
                    <?php };?>
                <td class="empty">Состояние
                    <td><?php echo $table[$i][2];?></td>
                    <tr>
                        <td>Рекомендации</td>
                        <td><?php echo $table[$i][3];?></td>
                    </tr>
                </td>
        </tr>
            <?php endfor;?>
                <?php endif;?>
    <!--<tr><td colspan="5"  class="emptyString"></td></tr>empty string
            <tr>
                <td class="nomber" rowspan="2">1</td>
                <td class="CheckName" rowspan="2">Проверка наличия файла robots.txt</td>
                <td class="statusErr" rowspan="2">Ошибка</td>
                <td class="empty">Состояние
                    <td>Файл robots.txt отсутствует</td>
                    <tr>
                        <td>Рекомендации</td>
                        <td>Программист: Создать файл robots.txt и разместить его на сайте.</td>
                    </tr>
                </td>
            </tr>-->
        </table>
        <?php //endif;?>
    </body>
</html>
<!--<table>
                        <tr>
                            <td>khk,jbjiuh</td>
                        </tr>
                        <tr>
                            <td>khk,jbjiuh</td>
                        </tr>
                    </table>-->