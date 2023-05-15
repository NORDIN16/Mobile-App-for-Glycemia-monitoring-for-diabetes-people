<?php 
class model_doctor{
    private $db;

    public function __construct() {
        session_start();
        $this->db = new PDO('mysql:host=localhost;dbname=pfa','root','');
    }

    public function login_doctor($doctor){
        $query = $this->db->prepare("select * from doctors join cathegories on doctors.id_cathegory=cathegories.id_cathegory where  email=?");
        $query->execute($doctor);
        return $query->fetch();      
    }

    public function cathegory($cathegory) {
        $query = $this->db->prepare("Select id_cathegory from cathegories where name_of_cathegory=?");
        $query->execute(array($cathegory));
        return $query->fetch() ;
    }

    public function update_profil($email,$password,$first_name,$last_name,$id_cathegory,$sexe,$user) {
        $data = [
            'email' => $email,
            'password' => $password,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'sexe' => $sexe,
            'id_cathegory' => $id_cathegory,
            'id' => $user,
        ];
        $sql = "UPDATE doctors SET email=:email, password=:password ,first_name=:first_name, last_name=:last_name, id_cathegory=:id_cathegory , sexe=:sexe WHERE id_doctor=:id";
        $stmt= $this->db->prepare($sql);
        $stmt->execute($data);
        return $email;
    }

    public function consultation($id_cathegory){
        $query = $this->db->prepare("select * from forum where id_categorie=?");
        $query->execute(array($id_cathegory));
        return $query->fetchAll();
    }

    public function see_more($id_question){
        $query = $this->db->prepare("select * from forum where id = ?");
        $query->execute(array($id_question));
        return $query->fetch();
    }

    public function see($id_question){
        $query = $this->db->prepare("select * from doctors D,forum F,answers A where A.id_doctor=D.id_doctor and F.id=A.id_consultation and A.id_consultation=?");
        $query->execute(array($id_question));
        return $query->fetchAll();
    }

    public function name_cathegory($id_cathegory){
        $query = $this->db->prepare("select name_of_cathegory from cathegories where id_cathegory=?");
        $query->execute(array($id_cathegory));
        return $query->fetch() ;
    }

    public function answer($id_question,$id_doctor,$answer){
        $query = $this->db->prepare("insert into answers values(?,?,?)");
        $result =$query->execute(array($id_question,$id_doctor,$answer));
        return $result ;
    }

    public function mark_answered($id,$statut) {
        $data = [
            'statut' => $statut,
            'id' => $id,
        ];
        $sql = 'UPDATE forum SET statut=:statut WHERE id=:id';
        $stmt= $this->db->prepare($sql);
        $stmt->execute($data);
    }

    public function history($id_docteur){
        $query = $this->db->prepare("select * from doctors D,forum F,answers A where A.id_doctor=D.id_doctor and F.id=A.id_consultation and A.id_doctor=?");
        $query->execute(array($id_docteur));
        return $query->fetchAll();
    }

    public function get_patient_id($patient){
        $query = $this->db->prepare("select id from patients where email=?");
        $query->execute(array($patient));
        return $query->fetch();
    }

    public function patient_profil($id_patient){
        $query = $this->db->prepare("select * from patients where id=?");
        $query->execute(array($id_patient));
        return $query->fetch();
    }

    public function patient_maladies($id_patient){
        $query = $this->db->prepare("select * from patient_maladie PM, maladies M where M.id_maladie=PM.id_maladie and id_patient=?");
        $query->execute(array($id_patient));
        return $query->fetchAll();
    }

    public function modify($answer,$id_consultation,$id_docteur) {
        $data = [
            'answer' => $answer,
            'id_consultation' => $id_consultation,
            'id_docteur' => $id_docteur,
        ];
        $sql = 'UPDATE answers SET answer=:answer WHERE id_consultation=:id_consultation and id_doctor=:id_docteur';
        $stmt= $this->db->prepare($sql);
        $stmt->execute($data);
        return true ;
    }


}
?>