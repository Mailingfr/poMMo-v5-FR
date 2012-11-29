<?php
class CSV{

    static function export($datas,$filename){
        header('Content-Type: text/csv;');
        header('Content-Disposition: attachment; filename="'.$filename.'.csv"');
        $i = 0;
        foreach($datas as $v){
            if($i==0){
                echo '"'.implode('";"',array_keys($v)).'"'."\n";
            }
            echo '"'.implode('";"',$v).'"'."\n";
            $i++;
        }
    }

}
