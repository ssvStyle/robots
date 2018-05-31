<!DOCTYPE html>
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
                background-color: #a2c4c9;
            }
            .statusErr {
                background-color: #e06666;
            }
            .statusOk {
                background-color: #93c47d;
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
            <?php require ('function.php');?>
            <h3>Check robots.txt</h3>
            
            <form method="post">
                  <p><input type="text" title="" name="url"></p>
                  <input type="submit" name="check" value="check">
            </form>
            <br>
            <?php if (isset($table)):?>
            <form action="download.php" method="POST">
                <input type="hidden" name="url" value="<?php echo $url;?>">
                <input type="submit" value="Save">
            </form>
             <?php endif;?>
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
        </table>
    </body>
</html>