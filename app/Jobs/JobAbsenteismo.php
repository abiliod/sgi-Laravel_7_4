<?php

namespace App\Jobs;

use App\Models\Correios\ModelsAuxiliares\Absenteismo;
use App\Models\Correios\ModelsAuxiliares\Alarme;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class JobAbsenteismo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $absenteismos, $dt_job, $dtmenos12meses;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($absenteismos, $dt_job, $dtmenos12meses)
    {
        $this->absenteismos = $absenteismos;
        $this->dt_job = $dt_job;
        $this->dtmenos12meses = $dtmenos12meses;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $absenteismos = $this->absenteismos;
        $dtmenos12meses = $this->dtmenos12meses;

        DB::table('absenteismos')
            ->where('data', '<', $dtmenos12meses)
            ->delete();

        ini_set('memory_limit', '512M');

        foreach($absenteismos as $dados) {
            foreach($dados as $registro) {

                $dt = substr($registro['data_evento'],6,4).'-'. substr($registro['data_evento'],3,2) .'-'. substr($registro['data_evento'],0,2);
                $matricula =   $this->deixarNumero($registro['matricula']);

                Absenteismo :: updateOrCreate([
                    'matricula' => $matricula,
                    'data_evento' => $dt,
                ],[
                    'matricula' => $matricula,
                    'data_evento' => $dt,
                    'nome' => $registro['nome'],
                    'lotacao' => $registro['lotacao'],
                    'cargo' => $registro['cargo'],
                    'motivo' => $registro['motivo'],
                    'dias' => $registro['dias'],
                ]);
            }
        }
        function deixarNumero($string){
            return preg_replace("/[^0-9]/", "", $string);
        }
        ini_set('memory_limit', '128M');
    }
}
