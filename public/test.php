<?php 
trait trait1{
    public function eat(){
        echo "This is trait1 eat";
    }
    public function drive(){
        echo "This is trait1 drive";
    }
}
trait trait2{
    public function eat(){
        echo "This is trait2 eat";
    }
    public function drive(){
        echo "This is trait2 drive";
    }
}
class cat{
    use trait1,trait2{
        trait1::eat insteadof trait2;
        trait1::drive insteadof trait2;
    }
}
class dog{
    use trait1,trait2{
        trait1::eat insteadof trait2;
        trait1::drive insteadof trait2;
        trait2::eat as eaten;
        trait2::drive as driven;
    }
}
$cat = new cat();
$cat->eat();
echo "<br/>";
$cat->drive();
echo "<br/>";
echo "<br/>";
echo "<br/>";
$dog = new dog();
$dog->eat();
echo "<br/>";
$dog->drive();
echo "<br/>";
$dog->eaten();
echo "<br/>";
$dog->driven();
