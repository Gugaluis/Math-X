<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;


class MainController extends Controller
{
    public function home():View
    {
        return view('home');
    }

    public function gerarExercicios(Request $request): View
    {
        // Validacao do formulario
        $request->validate([
            'check_sum' => 'required_without_all:check_subtraction,check_multiplication,check_division',
            'check_subtraction' => 'required_without_all:check_sum,check_multiplication,check_division',
            'check_multiplication' => 'required_without_all:check_subtraction,check_sum,check_division',
            'check_division' => 'required_without_all:check_subtraction,check_multiplication,check_sum',
            'number_one' => 'required|integer|min:0|max:999|lt:number_two',
            'number_two' => 'required|integer|min:0|max:999',
            'number_exercises' => 'required|integer|min:5|max:50',
        ]);
        
        // Busca as operações selecionadas
        $operations = [];

        if($request->check_sum) {$operations[] = 'sum'; }
        if($request->check_subtraction) {$operations[] = 'subtraction'; }
        if($request->check_multiplication) {$operations[] = 'multiplication'; }
        if($request->check_division) {$operations[] = 'division'; }

        // Retorna numeros (min e max)
        $min = $request->number_one;
        $max = $request->number_two;

        // Retorna numero de exercicios
        $numberExercises = $request->number_exercises;

        // Gera os exercicios
        $exercises = [];

        for ($index = 1; $index <= $numberExercises; $index++){
           $exercises[] = $this->generateExercise($index, $operations, $min, $max);
        }

        // Guarda os exercicios na sessão
        session([ 'exercises' => $exercises ]);

        return view( 'Operations', [ 'exercises' => $exercises ]);
    }

    public function printExercicios()
    {
        // Checando se os exercicios estão guardados na sessão
        if(!session()->has('exercises')) {
            return redirect()->route('home');
        }

        $exercises = session('exercises');

        echo '<pre>';
        echo '<h1>Exercicios de Matemática ('. env('APP_NAME') .') </h1>';
        echo '<hr>';

        foreach ($exercises as $exercise){
            echo '<h2><small>' . $exercise['exercise_number']. ' >> </small>' . $exercise['exercise'] . '</h2>';
        } 

        // Soluções
        echo '<hr>';
        echo '<small>Soluções</small><br>';
        foreach ($exercises as $exercise){
            echo '<small>' . $exercise['exercise_number']. ' >> ' . $exercise['solution'] . '</small><br>';
        }
        
    }

    public function exportarExercicios()
    {
        // Checando se os exercicios estão guardados na sessão
        if(!session()->has('exercises')) {
            return redirect()->route('home');
        }
    
        // Cria download do arquivo de exercicios 
    
        $exercises = session('exercises');
        $filename = 'exercises_' . env('APP_NAME') . '_' .  date('YmdHis') . '.txt';
        
        // Conteúdo dos enunciados dos exercícios
        $content = 'Exercicios de Matemática ('. env('APP_NAME') . ')' . "\n\n" ;
        foreach ($exercises as $exercise) {
            $content .= $exercise['exercise_number'] . ' > ' . $exercise['exercise'] . "\n";
        }
    
        // Adicionando as soluções ao conteúdo
        $content .= "\nSoluções \n" . str_repeat('-', 20) . "\n";  
        foreach ($exercises as $exercise) {
            $content .= $exercise['exercise_number'] . ' > ' . $exercise['solution'] . "\n";
        }
    
        // Retorna o conteúdo como resposta para download
        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
    

    private function generateExercise($index, $operations, $min, $max): array
    {
        $operation = $operations[array_rand($operations)];
        $number1 = rand($min, $max);
        $number2 = rand($min, $max);

        $exercise = '';
        $solution = '';
    
        switch ($operation){
            case 'sum':
                $exercise = "$number1 + $number2 =";
                $solution = $number1 + $number2;
            break;

            case 'subtraction':
                $exercise = "$number1 - $number2 =";
                $solution = $number1 - $number2;
            break;

            case 'multiplication':
                $exercise = "$number1 x $number2 =";
                $solution = $number1 * $number2;
            break; 

            case 'division':
                //Evitando erro de divisão por zero
                if($number2 == 0){
                    $number2 = 1;
                }

                $exercise = "$number1 : $number2 =";
                $solution = $number1 / $number2;
            break;                   
        }

        // Arredondando valores das respostas
        if(is_float($solution)){
            $solution = round($solution, 2);
        }

        return [
            'operation' => $operation,
            'exercise_number' => str_pad($index, 2, "0", STR_PAD_LEFT),
            'exercise' => $exercise,
            'solution' => "$exercise $solution"
        ];
    }

}