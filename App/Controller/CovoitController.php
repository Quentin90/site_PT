<?php
namespace App\Controller;
use App\Model\CommandeModel;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;   // pour utiliser request
use App\Model\CovoitModel;
use Symfony\Component\Validator\Constraints as Assert;   // pour utiliser la validation
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Security;
class CovoitController implements ControllerProviderInterface
{
    public function __construct()
    {
    }
    public function index(Application $app) {
        return $this->show($app);
    }
    public function show(Application $app) {
        $this->CovoitModel = new CovoitModel($app);
        $Covoit = $this->CovoitModel->getAllCovoit();
        return $app["twig"]->render('backOff/Covoit/homepage.html.twig',['data'=>$Covoit]);
    }
    public function add(Application $app) {
        if(isset($app["session"])&&$app ["session"]->get("droit")!="DROITadmin"){
            return "Vous n'avez pas les droits";
        }
        $this->CovoitModel = new CovoitModel($app);
        $Covoit = $this->CovoitModel->getAllCovoit();
        return $app["twig"]->render('backOff/Covoit/add.html.twig',['Covoit'=>$Covoit,'path'=>BASE_URL]);
        return "add Covoit";
    }
    public function validFormAdd(Application $app, Request $req) {
        if(isset($app["session"])&&$app ["session"]->get("droit")!="DROITadmin"){
            return "Vous n'avez pas les droits";
        }
        // var_dump($app['request']->attributes);
        if (isset($_POST['id_covoiturage']) && isset($_POST['depart']) && isset($_POST['arrive']) and isset($app["session"]) and isset($_POST['prix_covoiturage']) and isset($_POST['date_covoiturage'])) {
            $donnees = [
                'id_covoiturage' => htmlspecialchars($_POST['id_covoiturage']),// echapper les entrées
                'depart' => htmlspecialchars($req->get('depart')),
                'arrive' => htmlspecialchars($req->get('arrive')),
                'prix_covoiturage' => htmlspecialchars($req->get('prix_covoiturage')),
                'date_covoiturage' => htmlspecialchars($req->get('date_covoiturage')),
                'id_user' => $app ["session"]->get("id_user")
            ];
            if(! preg_match("/^[A-Za-z ]{2,}/",$donnees['depart'])) $erreurs['depart']='Doit être composé de 2 lettres minimum';
            if(! preg_match("/^[A-Za-z ]{2,}/",$donnees['arrive'])) $erreurs['arrive']='Doit être composé de 2 lettres minimum';
            if(! is_numeric($donnees['prix_covoiturage'])) $erreurs['prix_covoiturage']='Doit être composé de 1 chiffre minimum';
            if(! empty($erreurs))
            {
                $this->CovoitModel = new CovoitModel($app);
                $Covoit = $this->CovoitModel->getAllCovoit();
                return $app["twig"]->render('backOff/Covoit/add.html.twig',['donnees'=>$donnees,'erreurs'=>$erreurs,'Covoit'=>$Covoit]);
            }
            else
            {
                $this->CovoitModel = new CovoitModel($app);
                $this->CovoitModel->insertCovoit($donnees);
                return $app->redirect($app["url_generator"]->generate("Covoit.index"));
            }
        }
        else
            return $app->abort(404, 'error Pb data form Add');
    }
    public function delete(Application $app, $id) {
        if(isset($app["session"])&&$app ["session"]->get("droit")!="DROITadmin"){
            return "Vous n'avez pas les droits";
        }
        $this->CovoitModel = new CovoitModel($app);
        $Covoit = $this->CovoitModel->getAllCovoit();
        $this->CovoitModel = new CovoitModel($app);
        $donnees = $this->CovoitModel->getCovoit($id);
        return $app["twig"]->render('backOff/Covoit/delete.html.twig',['Covoit'=>$Covoit,'donnees'=>$donnees]);
        return "add Covoit";
    }
    public function validFormDelete(Application $app, Request $req) {
        if(isset($app["session"])&&$app ["session"]->get("droit")!="DROITadmin"){
            return "Vous n'avez pas les droits";
        }
        $id=$app->escape($req->get('id_Covoit'));
        if (is_numeric($id_Covoit)) {
            $this->CovoitModel = new CovoitModel($app);
            $this->CovoitModel->deleteCovoit($id_Covoit);
            return $app->redirect($app["url_generator"]->generate("Covoit.index"));
        }
        else
            return $app->abort(404, 'error Pb id form Delete');
    }
    public function edit(Application $app, $id) {
        if(isset($app["session"])&&$app ["session"]->get("droit")!="DROITadmin"){
            return "Vous n'avez pas les droits";
        }
        $this->CovoitModel = new CovoitModel($app);
        $Covoit = $this->CovoitModel->getAllCovoit();
        $this->CovoitModel = new CovoitModel($app);
        $donnees = $this->CovoitModel->getCovoit($id);
        return $app["twig"]->render('backOff/Covoit/edit.html.twig',['Covoit'=>$Covoit,'donnees'=>$donnees]);
        return "add Covoit";
    }
    public function validFormEdit(Application $app, Request $req) {
        if(isset($app["session"])&&$app ["session"]->get("droit")!="DROITadmin"){
            return "Vous n'avez pas les droits";
        }
        // var_dump($app['request']->attributes);
        if (isset($_POST['id_covoiturage']) && isset($_POST['depart']) && isset($_POST['arrive']) and isset($app["session"]) and isset($_POST['prix_covoiturage']) and isset($_POST['date_covoiturage'])) {
            $donnees = [
                'id_covoiturage' => htmlspecialchars($_POST['id_covoiturage']),// echapper les entrées
                'depart' => htmlspecialchars($req->get('depart')),
                'arrive' => htmlspecialchars($req->get('arrive')),
                'prix_covoiturage' => htmlspecialchars($req->get('prix_covoiturage')),
                'date_covoiturage' => htmlspecialchars($req->get('date_covoiturage')),
                'id_user' => $app ["session"]->get("id_user")
            ];
            if(! preg_match("/^[A-Za-z ]{2,}/",$donnees['depart'])) $erreurs['depart']='Doit être composé de 2 lettres minimum';
            if(! preg_match("/^[A-Za-z ]{2,}/",$donnees['arrive'])) $erreurs['arrive']='Doit être composé de 2 lettres minimum';
            if(! is_numeric($donnees['prix_covoiturage'])) $erreurs['prix_covoiturage']='Doit être composé de 1 chiffre minimum';
            $contraintes = new Assert\Collection(
                [
                    'id_Covoiturage' => [new Assert\NotBlank(),new Assert\('digit')],
                    'depart' => [
                        new Assert\NotBlank(['message'=>'Entrer le nom du point de depart']),
                        new Assert\Length(['min'=>2, 'minMessage'=>"Le nom doit faire au moins {{ limit }} caractères."])
                    ],
                    'id_covoiturage' => [new Assert\NotBlank(),new Assert\('digit')],
                    'arrive' => [
                        new Assert\NotBlank(['message'=>'Entrer le nom de la destination']),
                        new Assert\Length(['min'=>10, 'minMessage'=>"La description doit faire au moins {{ limit }} caractères."])
                    ],
                    'prix_covoiturage' => new Assert\(array(
                        ''    => 'numeric',
                        'message' => 'La valeur {{ value }} n\'est pas valide.',
                    ))
                ]);
            $errors = $app['validator']->validate($donnees,$contraintes);  // ce n'est pas validateValue
            //    $violationList = $this->get('validator')->validateValue($req->request->all(), $contraintes);
//var_dump($violationList);
            //   die();
            if (count($errors) > 0) {
                // foreach ($errors as $error) {
                //     echo $error->getPropertyPath().' '.$error->getMessage()."\n";
                // }
                // //die();
                //var_dump($erreurs);
                // if(! empty($erreurs))
                // {
                $this->CovoitModel = new CovoitModel($app);
                $Covoit = $this->CovoitModel->getAllCovoit();
                return $app["twig"]->render('backOff/Covoit/edit.html.twig',['donnees'=>$donnees,'errors'=>$errors,'erreurs'=>$erreurs,'Covoit'=>$Covoit]);
            }
            else
            {
                $this->CovoitModel = new CovoitModel($app);
                $this->CovoitModel->updateCovoit($donnees);
                return $app->redirect($app["url_generator"]->generate("Covoit.index"));
            }
        }
        else
            return $app->abort(404, 'error Pb id form edit');
    }
    public function connect(Application $app) {  //http://silex.sensiolabs.org/doc/providers.html#controller-providers
        $controllers = $app['controllers_factory'];
        $controllers->get('/', 'App\Controller\CovoitController::index')->bind('Covoit.index');
        $controllers->get('/show', 'App\Controller\CovoitController::show')->bind('Covoit.show');
        $controllers->get('/add', 'App\Controller\CovoitController::add')->bind('Covoit.add');
        $controllers->post('/add', 'App\Controller\CovoitController::validFormAdd')->bind('Covoit.validFormAdd');
        $controllers->get('/delete/{id}', 'App\Controller\CovoitController::delete')->bind('Covoit.delete')->assert('id', '\d+');;
        $controllers->delete('/delete', 'App\Controller\CovoitController::validFormDelete')->bind('Covoit.validFormDelete');
        $controllers->get('/edit/{id}', 'App\Controller\CovoitController::edit')->bind('Covoit.edit')->assert('id', '\d+');;
        $controllers->put('/edit', 'App\Controller\CovoitController::validFormEdit')->bind('Covoit.validFormEdit');
        return $controllers;
    }
}