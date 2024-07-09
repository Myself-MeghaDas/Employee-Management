<?php
class crud{
    public static function concet(){
        try {
            $con=new PDO('mysql:localhost=host; dbname=crud','root','');
            return $con;
        } catch (PDOException $error1) {
            echo "something went wrong,it was not possible to connect you to database !" .$error1->getMessage();
        }catch(Exception $error2){
            echo 'Generic error!' .$error2->getMessage();
        }
    }
    public static function selectdata(){
        $data=array();
        $p=crud::concet()->prepare('SELECT * FROM employee');
        $p->execute();
        $data=$p->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    public static function delete($id){
        $p=crud::concet()->prepare('DELETE FROM employee WHERE id=:id');
        $p->bindValue(':id',$id);
        $p->execute();
    }
}
?>