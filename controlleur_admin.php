<?php 
    include 'C:\xampp\htdocs\pfa\model\model_admin.php';
    class controlleur_admin{
        private $model;
        private $action;
        private $view;
        public static $user;
        
        public function __construct() { 
            $this->model = new model_admin() ;
            $this->action='all';
        }

        public function authentification() {
            if(!empty($_POST['admin'])&&!empty($_POST['password'])) {
                $admin = $_POST['admin'];
                $password = $_POST['password'];
                $row = $this->model->login_admin(array($admin));
                if ($password==$row['password']){
                    $_SESSION['authent']=true;
                    $_SESSION['email']=$admin;
                    $_SESSION['password']=$password;
                    $_SESSION['first_name']=$row['first_name'];
                    $_SESSION['last_name']=$row['last_name'];
                    header('location:..\view\view_admin\dashboard.php');
                }
            else {
                echo "<script>alert('password or email incorrecte');window.location='../View/view_admin/login_admin.html'</script>";
                }
            }
            else {
                header('location:..\view\view_admin\login_admin.html');
            }
        }

        public function modify_profil(){
            $password=$_POST['password'];
            $first_name=$_POST['first_name'];
            $last_name=$_POST['last_name'];
            $email=$_POST['email'];
            $result=$this->model->update_profil($password,$first_name,$last_name,$email);
            echo $result;
            if($result){
                $_SESSION['auth']=true;
                $_SESSION['email']=$email;
                $_SESSION['password']=$password;
                $_SESSION['first_name']=$first_name;
                $_SESSION['last_name']=$last_name;
                echo "<script>alert('Modification successfuly done. Go back to your profil');window.location='../view/view_admin/profil.php'</script>"; 
            }
        }

        public function maladies(){
            include 'C:\xampp\htdocs\pfa\view\view_admin\maladies.php' ;
            $maladies = $this->model->maladies();
            $this->view= new view_maladies("maladies");
            $this->view->maladies($maladies);
            $this->view->afficher();
        }

        public function delete_maladie() {
            $id_maladie = $_GET['id_maladie'];
            $this->model->delete_maladie($id_maladie);
            header('location:controlleur_admin.php?action=maladies');
        }

        public function new_maladie() {
            $maladie = $_POST['maladie'];
            $this->model->add_maladie($maladie);
            header('location:controlleur_admin.php?action=maladies');
        }

        public function cathegories(){
            include 'C:\xampp\htdocs\pfa\view\view_admin\cathegories.php' ;
            $cathegories = $this->model->cathegories();
            $this->view= new view_cathegories("cathegories");
            $this->view->cathegories($cathegories);
            $this->view->afficher();
        }

        public function delete_cathegorie() {
            $id_cathegorie = $_GET['id_cathegorie'];
            $this->model->delete_cathegorie($id_cathegorie);
            header('location:controlleur_admin.php?action=cathegories');
        }

        public function new_cathegorie() {
            $cathegorie = $_POST['cathegorie'];
            $this->model->add_cathegorie($cathegorie);
            header('location:controlleur_admin.php?action=cathegories');
        }

        public function consultations(){
            include 'C:\xampp\htdocs\pfa\view\view_admin\consultations.php' ;
            $consultations = $this->model->consultations();
            $this->view= new view_consultations("consultations");
            $this->view->consultations($consultations);
            $this->view->afficher();
        }

        public function delete_consultation() {
            $id_consultation = $_GET['id_consultation'];
            $this->model->delete_consultation($id_consultation);
            header('location:controlleur_admin.php?action=consultations');
        }

        public function users(){
            include 'C:\xampp\htdocs\pfa\view\view_admin\users.php' ;
            $users = $this->model->users();
            $this->view= new view_users("Patients");
            $this->view->users($users);
            $this->view->afficher();
        }

        public function delete_user() {
            $id_user = $_GET['id_user'];
            $this->model->delete_user($id_user);
            header('location:controlleur_admin.php?action=users');
        }

        public function doctors(){
            include 'C:\xampp\htdocs\pfa\view\view_admin\doctors.php' ;
            $doctors = $this->model->doctors();
            $this->view= new view_doctors("Doctors");
            $this->view->doctors($doctors);
            $this->view->afficher();
        }

        public function add_doctor(){
            $first_name=$_POST['first_name'];
            $last_name=$_POST['last_name'];
            $doctor=$_POST['email'];
            $password=$_POST['password'];
            $password1=$_POST['password1'];
            $sexe=$_POST['sex'];
            $cathegorie=$_POST['cathegory'];
            $id_cathegorie=$this->model->id_cathegory($cathegorie);
            $registred_doctor = $this->model->test_doctor($doctor);
            if(sizeof($registred_doctor)) {    
                echo "<script>alert('user alrady token. Please change it and retry');window.location='../view/view_admin/add_doctor.php'</script>";
            }
            if($password1 != $password){
                echo "<script>alert('unmatched passwords');window.location='../view/view_admin/add_doctor.php'</script>";
            }
            else{
                $result=$this->model->add_doctor($doctor,$password,$first_name,$last_name,$sexe,$id_cathegorie[0]);
                header('location:controlleur_admin.php?action=doctors');
            }
        }

        public function delete_doctor(){
            $id_doctor = $_GET['id_doctor'];
            $this->model->delete_doctor($id_doctor);
            header('location:controlleur_admin.php?action=doctors');
        }

        public function action() {
            $action="all";
            if(isset($_GET['action'])){
                $action=$_GET['action'];
            }
            if(isset($_POST['action'])){
                $action=$_POST['action'];
            }
            switch($action) {
                case 'signin':
                    $this->authentification();
                    break;
                case 'maladies':
                    $this->maladies();
                    break;
                case 'delete_maladie':
                    $this->delete_maladie();
                    break;
                case 'new_maladie':
                    $this->new_maladie();
                    break;
                case 'cathegories':
                    $this->cathegories();
                    break;
                case 'delete_cathegorie':
                    $this->delete_cathegorie();
                    break;
                case 'new_cathegorie':
                    $this->new_cathegorie();
                    break;
                case 'consultations':
                    $this->consultations();
                    break;
                case 'delete_consultation':
                    $this->delete_consultation();
                    break;
                case 'modify_profil':
                    $this->modify_profil();
                    break;
                case 'users':
                    $this->users();
                    break;
                case 'delete_user':
                    $this->delete_user();
                    break;
                case 'doctors':
                    $this->doctors();
                    break;  
                case 'add_doctor':
                    $this->add_doctor();
                    break; 
                case 'delete_doctor':
                    $this->delete_doctor();
                    break;   
            }
        }
    }
    $c = new controlleur_admin();
    $c->action();
?>