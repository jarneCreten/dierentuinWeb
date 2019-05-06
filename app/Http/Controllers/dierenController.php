<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Dier;
use Yajra\DataTables\DataTables;

class dierenController extends Controller
{
    function index()
    {
        return view('Dieren.data');
    }

    function getdata()
    {
        $dieren = Dier::select('id', 'diersoort', 'naam');
        return Datatables::of($dieren)
            ->addColumn('action', function($dier){
                return '<a href="#" class="btn btn-xs btn-primary edit" id="'.$dier->id.'"><i class="glyphicon glyphicon-edit"></i>Wijzigen</a>
                        <a href="#" class="btn btn-xs btn-danger delete" id="'.$dier->id.'"><i class="glyphicon glyphicon-remove"></i>Verwijderen</a>';
            })
            ->make(true);
    }
    function postdata(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'diersoort' => 'required',
            'naam'  => 'required',
        ]);

        $error_array = array();
        $success_output = '';
        if ($validation->fails())
        {
            foreach($validation->messages()->getMessages() as $field_name => $messages)
            {
                $error_array[] = $messages;
            }
        }
        else
        {
            if($request->get('button_action') == "insert")
            {
                $dier = new Dier([
                    'diersoort'    =>  $request->get('diersoort'),
                    'naam'     =>  $request->get('naam')
                ]);
                $dier->save();
                $success_output = '<div class="alert alert-success">Dier succesvol toegevoegd!</div>';
            }
            if($request->get('button_action') == 'update')
            {
                $dier = Dier::find($request->get('dier_id'));
                $dier->diersoort = $request->get('diersoort');
                $dier->naam = $request->get('naam');
                $dier->save();
                $success_output = '<div class="alert alert-success">Dier gewijzigd!</div>';
            }
        }
        $output = array(
            'error'     =>  $error_array,
            'success'   =>  $success_output
        );
        echo json_encode($output);
    }
    function fetchdata(Request $request)
    {
        $id = $request->input('id');
        $dier = Dier::find($id);
        $output = array(
            'diersoort'    =>  $dier->diersoort,
            'naam'     =>  $dier->naam
        );
        echo json_encode($output);
    }

    function removedata(Request $request)
    {
        $dier = dier::find($request->input('id'));
        if($dier->delete())
        {
            echo 'Dier verwijderd';
        }
    }
}
