<?php
namespace Abno\Abno360;

use Abno\Abno360\Contracts\Abno360UserContract;
use Abno\Abno360\Contracts\SelectOrganizationContract;
use Abno\Abno360\Models\Abno360User;
use Illuminate\Support\Facades\Auth;

class Abno360Service{

    public $url    = "http://127.0.0.1:8000/api/";
    public $apiUrl = "http://127.0.0.1:8000/api/";


    public function __construct()
    {
        $this->url = config("abno360.abno360url");
        $this->apiUrl = config("abno360.abno360url")."/api/";
    }
    public function addUserToLocal(Abno360UserContract $user){

       $Abno360User =  new Abno360User;
       $Abno360User->relative_id = $user->getRelativeId();
       $Abno360User->relative_type = $user->getRelative();
       $Abno360User->abno360_user_id = $user->getAbno360UserId();
       $Abno360User->save();
       return $Abno360User;
    }

    public function getAuthModel($guard=''){
        if($guard=='')
        $guard =  config("auth.defaults.guard");

        $provider = config("auth.guards.$guard.provider");
        $model = config("auth.providers.$provider.model");
        return $model;
    }
    public function loginInternalUser($user,$guard=''){

        $Abno360UserModel  = Abno360User::where("abno360_user_id",$user->id);

        if($Abno360UserModel->count()<=0){
            $model = $this->getAuthModel($guard);
            $userInteranl = new $model;
            $userInteranl = $userInteranl->where("email",$user->email);

            if($userInteranl->count()>0){
                $userInteranl = $userInteranl->first();
            }
            else{
                $userInteranl->email = $user->email;
                $userInteranl->phone_no = $user->phone_no;
                $userInteranl->save();
            }
            $Abno360UserContract = new Abno360UserContract($userInteranl);
            $Abno360UserContract->setAbno360UserId($user->id);
            $this->addUserToLocal($Abno360UserContract);


        }
        else{
            $Abno360User = $Abno360UserModel->first();
            $relative = $Abno360User->relative_type;
            $model = new $relative;
            $userInteranl  = $model->find($Abno360User->relative_id);

        }
        $Abno360UserContract = new Abno360UserContract($userInteranl,$guard);


        if($Abno360UserContract->beforeLogin()){

            Auth::guard($Abno360UserContract->auth())->login($userInteranl);
            request()->session()->regenerate();
            $Abno360UserContract->afterLogin();
            return $userInteranl;
        }
        return $userInteranl;

    }
    public function addUser(Abno360UserContract $info){

       $user =  $this->callApi("auth/register","POST",[
         "name"=>$info->getName(),
         "email"=>$info->getEmail(),
         "phone_no"=>$info->getPhone(),
         "password"=>$info->getPassword()
         ]
        );

        if($user->id)
        {
            $info->setAbno360UserId($user->id);
            $this->addUserToLocal($info);
        }

    }
    public function updateUser($info){

        $this->callApi("auth/update","POST",$info);

    }
    public function me(){
        if(!empty(session()->get("abno360token"))){
           $user =  $this->callApi("auth/me","POST",[],["Authorization: Bearer ".session()->get("abno360token")]);


           return $user;
        }
        else{
            throw new \Exception("User not logged in Abno360");
        }
    }

    public function loginURL($guard='',$redirect=""){
         if($guard==""){
             $guard =  config("auth.defaults.guard");
         }
         $clientId = config("abno360.client_id");

         return $this->url."/auth/login?rediect_uri=".urlencode(route("abno360-handle-redirect",["ins_uri"=>$redirect,"auth"=>$guard]))."&client_id=$clientId";
    }

    public function handleLogin($token){
        session()->put("abno360token",$token);
        $user =  $this->me();
        // $internalUser = $this->loginInternalUser($user,request("auth",''));
        $SelectOrganizationContract = new SelectOrganizationContract($user);

        if($SelectOrganizationContract->isUserConnectedWithAnyAuth()){
            if($SelectOrganizationContract->connectedAuthCount()==1){
                $firstContract =  $SelectOrganizationContract->getFirstContract();
                if($firstContract->auth()){
                    return redirect()->to($firstContract->redirectUrl());
                }
            }
            else{
                return $SelectOrganizationContract->render();
            }

        }
        else{
            $contract = config("abno360.default_auth_contract");;
            if(!empty(request("auth",''))){
                $contract = request("auth");
            }

            $object  = new $contract($user);
            if($object->register()){
                $SelectOrganizationContract = new SelectOrganizationContract($user);
                if($SelectOrganizationContract->isUserConnectedWithAnyAuth()){
                    if($SelectOrganizationContract->connectedAuthCount()==1){
                        $firstContract =  $SelectOrganizationContract->getFirstContract();
                        if($firstContract->auth()){
                            return redirect()->to($firstContract->redirectUrl());
                        }
                    }
                    else{
                        return $SelectOrganizationContract->render();
                    }
                }
                else{
                    throw new \Exception("There is some error while connecting");
                }
            }
            else{
                throw new \Exception("There is some error while registering user");
            }
        }

        // $urlToRedirect = request("ins_uri",'');
        // if($urlToRedirect!=''){
        //     return redirect()->to($urlToRedirect);
        // }


    }

    public function callApi($endpoint,$method="POST",$data=[],$headers=[]){
        $curl = curl_init();

        switch ($method){
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

               break;
            case "PUT":
               curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
               if ($data)
                  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
               break;
            default:
               if ($data)
               $endpoint = sprintf("%s?%s", $endpoint, http_build_query($data));
         }

         // OPTIONS:
         curl_setopt($curl, CURLOPT_URL, $this->apiUrl.$endpoint);
         curl_setopt($curl, CURLOPT_HTTPHEADER,
           array_merge([ 'Content-Type: application/x-www-form-urlencoded',
            ] ,$headers));
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
         // EXECUTE:

         $result = curl_exec($curl);
         $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
         if($httpcode==200){

            return json_decode($result);
         }
         else{
            echo $result;die;
            throw new \Exception($result);
         }

    }

    public function handleAuthContract($cls,$userID){
        $user =  $this->me();
        $clsObject = new $cls($user,$userID);
        if($clsObject->auth()){
            return response()->json(["status"=>"true","message"=>"success","redirect_url"=>$clsObject->redirectUrl()]);
        }
        else{

                return response()->json(["error"],400);


        }

    }
}

