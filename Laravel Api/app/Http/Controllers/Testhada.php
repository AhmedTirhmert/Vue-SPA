<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use DB;
use Validator;
use App\Models\User as Users;
use App\Models\Product;
use App\Models\Commande;
use App\Models\CommandeDet;
use App\Models\Fournisseur;
use App\Models\Categorie;
use App\Models\Banque;
use App\Models\Payment;
use App\Models\Employee;
use App\Models\EmployeePayment;

use Illuminate\Support\Facades\Hash;
use Eastwest\Json\Json;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Artisan;


use Faker\Factory as Faker;



class Testhada extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $ty = Users::all();
        return $ty;


    }
    public function GetUsers(Request $request)
    {

        $ty = Users::where('id_type',$request->type)->get();
        return $ty;


    }
    public function GetUserById(Request $request){
        $user=Users::where('id',$request->id)->get();
        $user->makeVisible(['password']);
        return response()->json($user);
    }
    public function UpdateUser(Request $request){
        $user=Json::decode($request->user);
        $db_user=Users::where('id',$user->id)->get();
        $userpass = Users::select('password')->where('id',$user->id)->get();
        $responseArr= Array();
        $userpass->makeVisible(['password']);
        $db_user->makeVisible(['password']);
        //return response()->json($user);
        if (
            self::validateUserName($user->name)
            && self::validateUserEmail($user->email)
            && (Hash::check($user->currentpass,$userpass[0]['password'] ))
            && ($user->newpass == $user->confnewpass)
            )  {
                // $updatedUser['name']=$user->name;
                // $updatedUser['email']=$user->email;
                // $updatedUser['password']=Hash::make($user->password);
                // $db_user->update($updatedUser);

                Users::where('id',$user->id)
                ->update([
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => Hash::make($user->newpass),
                ]);
            // $db_user[0]->name  = $user->name;
            // $db_user[0]->email  = $user->email;
            // $db_user[0]['password']  = Hash::make($user->password);
            // $db_user[0]->save();
            return response()->json(
                        [
                        'Success' => true,
                        'Message' => 'The admin '. Users::where('id',$user->id)->select('name')->first()['name'].' Updated successfully'
                        ]
                    );
        }else{
            if(!self::validateUserName($user->name)){
                $responseArr['email']="email is already taken";
            }
            if(!self::validateUserEmail($user->email)){
                $responseArr['name']="name is already taken";
            }
            if(!Hash::check($user->currentpass,$userpass[0]->password )){
                $responseArr['currentpass']="current password is incorrect ";
            }
            if(!$user->newpass == $user->confnewpass){
                $responseArr['newpass']="new password don't match";
            }
            return response()->json([
                'errors' => true,
                'errosmessage' => $responseArr,
            ]);
        }
    }

    public function addUser(Request $request){
        $user=Json::decode($request->user);
        $responseArr= Array();
        if (
            self::validateUserName($user->name)
            && self::validateUserEmail($user->email)
            &&($user->password == $user->confirmPassword)
            )  {
                // $updatedUser['name']=$user->name;
                // $updatedUser['email']=$user->email;
                // $updatedUser['password']=Hash::make($user->password);
                // $db_user->update($updatedUser);
                Users::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => Hash::make($user->password),
                    'id_type' => $user->type
                ]);
            // $db_user[0]->name  = $user->name;
            // $db_user[0]->email  = $user->email;
            // $db_user[0]['password']  = Hash::make($user->password);
            // $db_user[0]->save();
            return response()->json(
                        [
                        'Success' => true,
                        'type' => 'The '.($user->type == 1)? 'Admin ' : 'Client ' ,
                        'Message' => ' '. $user->name.' Added  successfully'
                        ]
                    );
        }else{
            if(!self::validateUserEmail($user->email)){
                $responseArr['email']="Email is already taken";
            }
            if(!self::validateUserName($user->name)){
                $responseArr['name']="Username is already taken";
            }
            if($user->password != $user->confirmPassword){
                $responseArr['Password']="Passwords Don't much";
            }
            return response()->json([
                'errors' => true,
                'errosmessage' => $responseArr,
            ],500);
        }

    }
    private function validateUserName($name)
    {
        $name_ex=Users::where('name',$name)->count()==1;
        if (!$name_ex){
            return true;
        }else{
            return false;
        }


    }
    private function validateUserEmail($email )
    {
        $email_ex=Users::where('email',$email)->count()==1;
        if (!$email_ex){
            return true;
        }else{
            return false;
        }


    }
    public function deleteUser(Request $request)
    {
        $user = Users::where('id',$request->id)->first();
        $name = $user->name;
        $user->delete();
        return response()->json([
            // 'deletedUser' => Users::withTrashed()->where('id',$request->id)->first(),
            'message'=>'User | '.$user->name.'| Deleted successfully ',
        ]);
    }



    public function Clientcommandes(Request $request)
    {
        $commandes = Array();
        $commande = Array();
        $res =  Commande::where('id_client',$request->id_client)->with(['admin','state'])->get();
        foreach ($res as $re ) {
            $commande['cmnd_id']=$re['numero_cmnd'];
            $commande['Message']=$re['Message'];
            $commande['Date']=$re['date_effectue'];
            $commande['admin']=$re['admin']['name'];
            $commande['state']=$re['state']['etat'];
            array_push($commandes,$commande);
        }
        // return DB::table('commandes')
        //                                       ->join('users','users.id','=','commandes.id_client')
        //                                       ->select('numero_cmnd','name','date_effectue')
        //                                       ->orderByRaw('date_effectue DESC')
        //                                       ->get();
        // return DB::table('commandes')->select('name','Message','id_etat')
        // ->leftjoin('users','commandes.id_admin','=','users.id')
        // ->where('commandes.numero_cmnd',$request->id_client)
        // ->get();
        return $commandes;
    }
    public function ProfileDetails(Request $request)
    {
        $Profile = Users::where('id',$request->id)->first();
         $Profile->makeVisible('created_at');
        return response()->json([
            'profile' => $Profile,
            'stats' =>  self::detstest($request->id)
        ]);
    }
    public function detstest($Id)
    {
        $ids= Commande::select('numero_cmnd')->where('id_client',$Id)->get();
        $cmnds_ids=Array();
        foreach ($ids as $id => $cmndId) {
            $cmnds_ids[$id]=$cmndId->numero_cmnd;
        }
        $product_total=0;
        $price_total=0;
        //return CommandeDet::whereIn('numero_cmnd',$cmnds_ids)->with(['Product'])->get();
        $commandes =  CommandeDet::whereIn('numero_cmnd',$cmnds_ids)->with(['Product'])->get();
        foreach ($commandes as $commande) {
            $product_total += $commande->qte_cmnd;
            $price_total +=  $commande->qte_cmnd * $commande->product[0]->prix_unitaire;
        }
        return response()->json([
                                    'Transactions'=>$price_total,
                                    'Products'=>$product_total
                                ]);
    }
    public function CommandeDetails(Request $request)
    {
        $Details=CommandeDet::where('numero_cmnd',$request->commandeId)->with(['Product','Product.Fournisseur','Product.Category'])->get();
        $response=Array();
        $single=Array();
        foreach ($Details as $Detail) {
            $single['Quantity']=$Detail->qte_cmnd;
            $single['Reference']=$Detail->product[0]->Reference;
            $single['Desegnation']=$Detail->product[0]->designation;
            $single['Price']=$Detail->product[0]->prix_unitaire;
            $single['Category']=$Detail->product[0]->category->libelle;
            $single['Fournisseur']=$Detail->product[0]->fournisseur->name;
            array_push($response,$single);
        }
        return response()->json($response);
    }
    public function getProducts()
    {
        return Product::with(['Category','Fournisseur'])->orderBy('code_produit','DESC')->get();
    }
    public function getCatsFrnss()
    {
        return response()->json([
            'categories' => Categorie::select('code_categorie as value','libelle as text')->get(),
            'fournisseurs' => Fournisseur::select('code_fournisseur as value','name as text')->get(),

        ]);;
    }
    public function addProduct(Request $request)
    {

        $Validation = $request->validate([
            'Reference' => 'required|regex:/^\S*$/u|size:8|unique:products',
            'designation' => 'required|unique:products|min:8',
            'category' => 'required',
            'fournisseur' => 'required',
            'price' => 'required|gt:0',
            'quantity' => 'required|integer|gt:0',
        ]);
        Product::create([
            'Reference' => $request->Reference,
            'designation' => $request->designation,
            'code_categorie' => $request->category,
            'code_fournisseur' => $request->fournisseur,
            'prix_unitaire' => $request->price,
            'quantite' => $request->quantity
        ]);

            return response()->json([
                'message' => 'Product '. $request->designation .'Added Succesfully'
            ]);
    }
    public function editProduct(Request $request)
    {
        $product=Product::FindOrFail($request->id);
        $Validation = $request->validate([
            'Reference' => ['required','regex:/^\S*$/u','size:8',Rule::unique('Products')->ignore($product)],
            'designation' => ['required','min:8',Rule::unique('Products')->ignore($product)],
            'category' => 'required',
            'fournisseur' => 'required',
            'price' => 'required|gt:0',
        ]);
        $pro = Product::find($request->id)->update([
            'Reference' => $request->Reference,
            'designation' => $request->designation,
            'code_categorie' => $request->category,
            'code_fournisseur' => $request->fournisseur,
            'prix_unitaire' => $request->price,
        ]);

            return response()->json([
                'message' => 'Product '. $request->designation .' Updated Succesfully'
            ]);
    }
    public function deleteProduct(Request $request)
    {

        $product=Product::FindOrFail($request->id);
        $product->delete();

            return response()->json([
                'message' => 'Product '. $product->designation .' Deleted Succesfully'
            ]);
    }
    public function productHistory(Request $request)
    {
        return $cmnds = CommandeDet::where('code_produit',$request->code)->with(['Commande','Commande.Client','Commande.State'])->get();
    }
    public function addCat(Request $request)
    {

        $Validation = $request->validate([
            'libelle' => 'required|unique:categories|min:3',
        ]);
        Categorie::create([
            'libelle' => $request->libelle,
        ]);

            return response()->json([
                'message' => 'Categorie :  '. $request->libelle .'  Added Succesfully'
            ]);
    }
    public function addFrns(Request $request)
    {

        $Validation = $request->validate([
            'name' => 'required|unique:fournisseurs|min:8|max:30',
            'adresse' => 'required|min:20',
            'email' => 'required|email|unique:fournisseurs|min:3',
            'telephone' => 'required|unique:fournisseurs|min:14|max:20',
        ]);
        Fournisseur::create([
            'name' => $request->name,
            'adresse' => $request->adresse,
            'email' => $request->email,
            'telephone' => $request->telephone,
        ]);

            return response()->json([
                'message' => 'Fournisseur :  '. $request->name .'  Added Succesfully'
            ]);
    }
    public function UpdateFrns(Request $request)
    {
        $Frns= Fournisseur::find($request->id);
        $name = $Frns->name;
        $Validation = $request->validate([
            'name' => ['required','min:8','max:30',Rule::unique('fournisseurs')->ignore($Frns)],
            'adresse' => 'required|min:20',
            'email' => ['required','email','min:3',Rule::unique('fournisseurs')->ignore($Frns)],
            'telephone' => ['required','min:14','max:20',Rule::unique('fournisseurs')->ignore($Frns)],
        ]);
        $Frns->update([
            'name' => $request->name,
            'adresse' => $request->adresse,
            'email' => $request->email,
            'telephone' => $request->telephone,
        ]);
        return response()->json([
            'message' => 'Fournisseur :  '. $name .'  Updated to '. $Frns->name .' Succesfully'
        ]);

    }
    public function getFrnss(Request $request)
    {
        return Fournisseur::orderBy('code_fournisseur','DESC')->get();
    }
    public function DeleteFrns(Request $request)
    {
        $frns = Fournisseur::findOrfail($request->id);
        $frns->delete();
        return response()->json([
            'message' => 'Fournisseur '.$frns->name.' Deleted Successfully'
        ]);
    }
    public function FrnsProfile(Request $request)
    {
        $products = Fournisseur::with(['Product','Product.category'])->find($request->id);
        return response()->json([
            'products' => $products,
        ]);
    }



    public function testing(Request $request)
    {

        $products=Product::limit(8)->get();
        $respo = Array();
        foreach ($products as  $product) {
            $cmds=DB::table('concerne')
                ->join('commandes','commandes.numero_cmnd','=','concerne.numero_cmnd')
                ->select('concerne.qte_cmnd as qty','commandes.date_effectue as date')
                ->where('concerne.code_produit',$product->code_produit)
                ->orderby('date','ASC')
                ->get();
                array_push($respo,[
                    'name'=>$product->designation,
                    'data'=>$cmds
                ]);

        }

        // $cmds=DB::table('concerne')
        //         ->join('commandes','commandes.numero_cmnd','=','concerne.numero_cmnd')
        //         ->join('products','products.code_produit','=','concerne.code_produit')
        //         ->select('concerne.qte_cmnd as qty','commandes.date_effectue as date','products.designation as Product')
        //         ->orderby('date','ASC')
        //         ->get();

        return response()->json(['Res'=>$respo]);
    }





    public function GetPayments(Request $request)
    {
        return response()->json(Payment::with('Banque')->orderBy('Exp_date','DESC')->get());
    }
    public function GetBanques(Request $request)
    {
        return response()->json(Banque::select('id as value','name as text')->get());
    }
    public function AddPayment(Request $request)
    {
        $Validation = $request->validate([
            'Exp_date' => 'bail|required',
            'Banque_id' => 'bail|required',
            'Payed' => 'bail|required',
            'amount' => 'bail|required|gt:0',
        ]);
        $addedPayment = Payment::create([
            'Exp_date' => $request->Exp_date,
            'Banque_id' => $request->Banque_id,
            'Payed' => !(!$request->Payed),
            'amount' => $request->amount,
        ]);
        return response()->json([
            'message' => 'Payment '. $addedPayment->id .'  Added Succesfully'
        ]);
    }
    public function UpdatePayment(Request $request)
    {
        $Validation = $request->validate([
            'Exp_date' => 'bail|required',
            'Banque_id' => 'bail|required',
            'Payed' => 'bail|required',
            'amount' => 'bail|required|gt:0',
        ]);
        $addedPayment = Payment::findOrfail($request->id)->update([
            'Exp_date' => $request->Exp_date,
            'Banque_id' => $request->Banque_id,
            'Payed' => !(!$request->Payed),
            'amount' => $request->amount,
        ]);
        return response()->json([
            'message' => 'Payment N° : '. $request->id .'  Updated Succesfully'
        ]);
    }
    public function AddBanque(Request $request)
    {
        $Validation = $request->validate([
            'name' => 'bail|unique:banques|required|min:4',
        ]);
        $addedBanque = Banque::create([
            'name' => $request->name,
        ]);
        return response()->json([
            'message' => 'Banque '. $addedBanque->name .'  Added Succesfully'
        ]);
    }


    public function AddEmployee(Request $request)
    {

        $Validation = $request->validate([
            'name' => 'bail|required|unique:employees|min:8|max:30',
            'cin' => 'bail|required|unique:employees|min:7|max:8',
            'salary' => 'bail|required|integer|gt:0',
            'phone' => 'bail|min:10|max:20',
            'work_place' => 'bail|required',
        ]);
        Employee::create([
            'name' => $request->name,
            'work_place' => $request->work_place,
            'phone' => $request->phone,
            'cin' => $request->cin,
            'salary' => $request->salary,
        ]);

            return response()->json([
                'message' => 'Employee :  '. $request->name .'  Added Succesfully'
            ]);
    }
    public function GetEmployees(Request $request)
    {
        return response()->json(employee::all());
    }




    public function AddEmployeePayment(Request $request)
    {

        $Validation = $request->validate([
            'Payment' => 'bail|required|integer|gt:0',
            'employee_id' => 'bail|required',
            'date' => 'bail|required|date|before_or_equal:tomorrow',
        ]);

            EmployeePayment::create([
                'credit' => 0,
                'debut' => $request->Payment,
                'employee_id' => $request->employee_id,
                'date' => $request->date,
            ]);

            return response()->json([
                'message' => 'Payment : Added Succesfully'
            ]);
    }
    public function EmployeeProfile(Request $request)
    {
        $employee = Employee::with(['Payment' => function($q) {$q->orderby('date','DESC');}])->find($request->id);
            return response()->json([
                'Payments' => $employee->payment,
            ]);
    }





    //Commandes Section
    public function GetCommandes(Request $request)
    {

        return response()->json(Commande::with([
            'client',
            'admin',
            'state',
            'Details',
            'Details.Product',
            'Details.Product.Category'
            ])->orderBy('numero_cmnd','DESC')->get());
    }
    public function NewCommandeProductsClients(Request $request)
    {

        return response()->json([
            'Products' =>Product::select(['quantite','prix_unitaire','designation','Reference','code_produit'])->get(),
            'Clients'=>Users::where('id_type',2)->get()
            ]
        );
    }
    public function AddNewCommande(Request $request)
    {
        $commande = Json::decode($request->commande);


        DB::beginTransaction();
        try {
            $cmnd=Commande::create([
                "id_client" => $commande->client_id,
                "id_etat" => $commande->payed+1,
                "id_admin" => 1,
                "Message" => $commande->note,
                "date_effectue" => $commande->date,
            ]);
            foreach ($commande->products as $pro) {
                CommandeDet::create([
                    "code_produit"=>$pro->code_produit,
                    "numero_cmnd"=> $cmnd->numero_cmnd,
                    "qte_cmnd"=> $pro->qty
                ]);
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();


            if ($ex->errorInfo[0]=='23000') {
                return response()->json(['errorMsg' => 'Product Selected Twice! Please Correct And Re-Submit '], 500);
            }
            return response()->json(['error' => $ex], 500);
        }
        return response()->json("commande inserted succefully");
    }


    public function UpdateCommande(Request $request)
    {
         $commande = Json::decode($request->commande);


       //return response()->json($commande->numero_cmnd);
        DB::beginTransaction();
        try {
            DB::enableQueryLog();
            CommandeDet::where('numero_cmnd',$commande->numero_cmnd)->delete();
            //dd(DB::getQueryLog());
            $cmnd=Commande::where('numero_cmnd',$commande->numero_cmnd)->first()
            ->update([
                "id_client" => $commande->client_id,
                "id_etat" => $commande->payed ? 1 : 2,
                "id_admin" => 2,
                "Message" => $commande->note,
                "date_effectue" => $commande->date,
            ]);
            foreach ($commande->Articles as $pro) {
                CommandeDet::create([
                    "code_produit"=>$pro->code_produit,
                    "numero_cmnd"=>$commande->numero_cmnd,
                    "qte_cmnd"=>$pro->qty,
                ]);
            }
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            if ($ex->errorInfo[0]=='23000') {
                return response()->json(['errorMsg' => 'Product Selected Twice! Please Correct And Re-Submit '], 500);
            }
            return response()->json(['error' => $ex], 500);
        }
        return response()->json("commande Updated succefully");
    }
    public function DeleteCommande(Request $request)
    {

        try {
            Db::beginTransaction();
            $delete = Commande::find($request->cmnd_id)->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
            return response()->json(['error' => $th], 500);
        }
        return response()->json("Commande ".$request->cmnd_id." Deleted succefully");
    }
    public function ProductsDets()
    {

        return Product::select('designation','quantite')->get();
        // $prdcts = Product::select('code_produit');
        // return response()->json([
        //     "Names"=>,
        //     "Data"=>,
        // ]);
    }

































    public function SeedDb()
    {
        Artisan::call('db:seed');
        return response()->json("DB SEEDED SUCCESSFULLY MAYBE ");
    }
}
