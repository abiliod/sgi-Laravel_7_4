@if ($count >= 1)
    @if( (isset($plplistapendentes)) && (!empty($plplistapendentes)) )
        <div id="aprimoramento">
            <span>
            Em análise à Relação de Listas Pendentes do sistema SARA, consulta em {{ date( 'd/m/Y' , strtotime($dtfim))}}, e aos eventos registrados no sistema SRO, constataram-se as inconsistências relacionadas a seguir:
                1) Rastrear os objetos a fim de identificar Objetos com registro de POSTAGEM e SEM eventos posteriores que indiquem sua entrada na ECT. Se houver registros nestas condições, registrar como Falta de Conferência.<br>
                2) Verificar se existem objetos que passaram no Fluxo Postal, mas não tem o evento de POSTAGEM. Se houver registros nestas condições, registrar que não houve a contabilização.<br>
                3) Objetos com Registros Postado e Encaminhado ou sem eventos, considerar o item como Conforme.<br>
                4) Defina a situação para cada objeto.<br>
                5) Caso  este item esteja inconforme apagar as instruções de 1 a 5. para permanecer apenas as suas observações.
            </span>
        </div>
        <div id="historico">
             <table class="highlight">
                <thead>
                    <tr>
                        <th>Lista</th>
                        <th>PLP</th>
                        <th>Objeto</th>
                        <th>Cliente</th>
                        <th>Data da Postagem</th>
                        <th>Situação</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($plplistapendentes as $plplistapendente)
                    <tr>
                        <td>{{ $plplistapendente->lista }}</td>
                        <td>{{ $plplistapendente->plp }}</td>
                        <td>{{ $plplistapendente->objeto }}</td>
                        <td>{{ $plplistapendente->cliente }}</td>
                        <td>{{(isset($plplistapendente->dh_lista_postagem) && $plplistapendente->dh_lista_postagem == ''  ? '   ----------  ' : \Carbon\Carbon::parse($plplistapendente->dh_lista_postagem)->format('d/m/Y'))}}</td>
                        <th>Falta de Conferencia ou Sem Contabilização</th>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div id="historico1"></div>
    @endif
@else
<div id="aprimoramento">
    <span class="lever" text-align="rigth">
        Em análise à Relação de Listas Pendentes do sistema SARA, Planilha disponibilizada com maior data de registro de objetos pendentes datado de
        {{ date( 'd/m/Y' , strtotime($dtfim))}}, e aos eventos registrados no sistema SRO, constataram-se que não havia pendência para a unidade inspecionada.
    </span>
</div>
<div id="historico"></div>
<div id="historico1"></div>
@endif

<input type="hidden"  id="totalfalta" value="{{ isset($total) ? $total : '' }}">
<input type="hidden"  id="totalrisco" value="0.00">
<input type="hidden"  id="totalsobra" value="0.00">
