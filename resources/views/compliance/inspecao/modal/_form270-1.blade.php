
@if($count>=1)
    <div id="aprimoramento">
        <span class="lever" >Em Análise aos dados do Sistema WebCont – Composição Analítica da conta 11202.994000, posição de
            {{ substr($competencia->competencia, 4, 2)}}/{{ substr($competencia->competencia, 0, 4)}}, constatou-se a existência de {{$count}} débitos de empregado sem regularização há mais de 90 dias, conforme relacionado a seguir:</span><br>
    </div>

<div id="historico">
    <table class="highlight">
        <thead>
            <tr>
                <th>Data</th>
                <th>Documento</th>
                <th>Histórico</th>
                <th>Matricula</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
        @foreach($debitoempregados as $debitoempregado)
            <tr>
                <td>{{ date( 'd/m/Y' , strtotime($debitoempregado->data))}}</td>
                <td>{{ $debitoempregado->documento }}</td>
                <td>{{ $debitoempregado->historico }}</td>
                <td>{{ $debitoempregado->matricula }}</td>
                <td>{{  'R$ '.number_format($debitoempregado->valor, 2, ',', '.') }}</td>

            </tr>
        @endforeach
        <tr>
                <td>Total</td>
                <td>{{  'R$ '.number_format($total, 2, ',', '.') }}</td>
        </tr>
        </tbody>
    </table>
</div>
@else
    <div id="aprimoramento">
          <span class="lever">Em Análise aos dados do Sistema WebCont – Composição Analítica da conta 11202.994000, verificaram a posição do mês
              {{ $competencia }},

              constataram que não havia Histórico de Pendências de Débitos de Empregados maior que 90 dias.</span><br>
    </div>
    <div id="historico"></div>
@endif
<div id="historico1"></div>
<input type="hidden"  id="totalfalta" value="{{ isset($total) ? $total : '' }}">
<input type="hidden"  id="totalrisco" value="0.00">
<input type="hidden"  id="totalsobra" value="0.00">


