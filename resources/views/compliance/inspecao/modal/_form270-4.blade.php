


@if($total == 0.00)
<div id="aprimoramento">
    <span class="lever" text-align="rigth">
        Em análise ao Relatório "Saldo de Numerário em relação ao Limite de Saldo",
         do sistema BDF, referente ao período de {{ $mesAtrasado}}
          a  {{ $mesatual}}, constataram que não houve descumprimento do limite de saldo estabelecido para a unidade.
    </span>
</div>
@else
    <div id="aprimoramento">
        <span class="lever" text-align="rigth">
            Em análise ao Relatório “Saldo de Numerário em relação ao Limite de Saldo", do sistema BDF,
            referente ao período de {{ $mesAtrasado}} a  {{ $mesatual}}, constatou-se que o limite do
            saldo estabelecido para a unidade foi descumprido em XXxxxxx dias, o que correspondia a uma média de xxx ocorrências por mês (xx%), conforme detalhado a seguir:
        </span>
    </div>
    <div id="historico">
    @if(isset($data)&&(!empty($data)))
        @foreach($data as $meses)
            @if(substr($meses['MesReferencia'],0,2) == $mesAtrasado)
                <ul>
                    <li>
                    <p>
                        a)&nbsp;No mês {{str_replace('-', '/', $meses['MesReferencia'])}}: Houve {{$meses['Ocorrencias']}} &nbsp; ocorrências, e o valor total ultrapassado do Limite foi &nbsp; {{ 'R$ '.number_format($meses['AcumuladoMes'], 2, ',', '.') }}:
                    </p>
                        @if($meses['AcumuladoMes'] != 0.00)
                        <p><span>Sub Total </span>{{ isset($total) ?  'R$  '.number_format($total= $total + $meses['AcumuladoMes'], 2, ',','.') : '' }}</p>
                            <input type="hidden"  id="totalfalta" value="0.00">
                        @endif
                        </li>
                        <table class="highlight">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Saldo de Numerário</th>
                                    <th>Limite de Saldo</th>
                                    <th>Diferença</th>
                                </tr>
                            </thead>
                        <tbody>
                        @switch(substr($mesAtrasado,0,2))

                                    @case('01')
                                        @foreach ($sl02bdfs01  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        @break

                                    @case('02')
                                        @foreach ($sl02bdfs02  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        @break

                                    @case('03')
                                    @foreach ($sl02bdfs03  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        @break

                                    @case('04')
                                    @foreach ($sl02bdfs04  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>

                                        @endforeach
                                        @break

                                    @case('05')
                                        @foreach ($sl02bdfs05  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        @break

                                    @case('06')
                                    @foreach ($sl02bdfs06  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        @break
                                    @case('07')
                                    @foreach ($sl02bdfs07  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        @break

                                    @case('08')
                                    @foreach ($sl02bdfs08  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        @break

                                    @case('09')
                                    @foreach ($sl02bdfs09  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        @break

                                    @case('10')
                                    @foreach ($sl02bdfs10  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        @break

                                    @case('11')
                                    @foreach ($sl02bdfs11  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        @break

                                    @case('12')
                                    @foreach ($sl02bdfs12  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        @break
                                    @default
                                    <tr>
                                        <th colspan='4'>Não houve Registros</th>
                                    </tr>
                                @endswitch
                        </tbody>
                        </table>
                    </ul>
                @endif

            @if(substr($meses['MesReferencia'],0,2) == $mesPassado)

            <ul>
                    <li>
                    <p>
                        b)&nbsp;No mês {{str_replace('-', '/', $meses['MesReferencia'])}}: Houve {{$meses['Ocorrencias']}} &nbsp; ocorrências, e o valor total ultrapassado do Limite foi &nbsp; {{ 'R$'.number_format($meses['AcumuladoMes'], 2, ',', '.') }}:
                    </p>
                        @if($meses['AcumuladoMes'] != 0.00)

                            <input type="hidden"  id="totalfalta" value="0.00">
                            <p><span>Sub Total</span>{{ isset($total) ?  'R$  '.number_format($total= $total + $meses['AcumuladoMes'], 2, ',', '.') : '' }}</p>
                        @endif
                        </li>
                        <table class="highlight">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Saldo de Numerário</th>
                                    <th>Limite de Saldo</th>
                                    <th>Diferença</th>
                                </tr>
                            </thead>
                        <tbody>
                        @switch(substr($mesPassado,0,2))

                                    @case('01')
                                        @foreach ($sl02bdfs01  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        @break

                                    @case('02')
                                        @foreach ($sl02bdfs02  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        @break

                                    @case('03')
                                    @foreach ($sl02bdfs03  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        @break

                                    @case('04')
                                    @foreach ($sl02bdfs04  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>

                                        @endforeach
                                        @break

                                    @case('05')
                                        @foreach ($sl02bdfs05  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        @break

                                    @case('06')
                                    @foreach ($sl02bdfs06  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        @break
                                    @case('07')
                                    @foreach ($sl02bdfs07  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        @break

                                    @case('08')
                                    @foreach ($sl02bdfs08  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        @break

                                    @case('09')
                                    @foreach ($sl02bdfs09  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        @break

                                    @case('10')
                                    @foreach ($sl02bdfs10  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        @break

                                    @case('11')
                                    @foreach ($sl02bdfs11  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        @break

                                    @case('12')
                                    @foreach ($sl02bdfs12  as $tabela)
                                            <tr>
                                                <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                                <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                                <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                        @break
                                    @default
                                    <tr>
                                        <th colspan='4'>Não houve Registros</th>
                                    </tr>
                                @endswitch
                        </tbody>
                        </table>
                    </ul>
            @endif

            @if(substr($meses['MesReferencia'],0,2) == $mesatual)

    <ul>
            <li>
            <p>
                c)&nbsp;No mês {{str_replace('-', '/', $meses['MesReferencia'])}}: Houve {{$meses['Ocorrencias']}} &nbsp; ocorrências, e o valor total ultrapassado do Limite foi &nbsp; {{ 'R$'.number_format($meses['AcumuladoMes'], 2, ',', '.') }}:
            </p>
                @if($meses['AcumuladoMes'] != 0.00)
                <p><span>Sub Total </span> {{  isset($total) ?  'R$  '.number_format($total , 2, ',', '.') : '' }}</p>

                @endif


                </li>
                <table class="highlight">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Saldo de Numerário</th>
                            <th>Limite de Saldo</th>
                            <th>Diferença</th>
                        </tr>
                    </thead>
                <tbody>
                @switch(substr($mesatual,0,2))

                            @case('01')
                                @foreach ($sl02bdfs01  as $tabela)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                        <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                @break

                            @case('02')
                                @foreach ($sl02bdfs02  as $tabela)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                        <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                @break

                            @case('03')
                            @foreach ($sl02bdfs03  as $tabela)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                        <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                @break

                            @case('04')
                            @foreach ($sl02bdfs04  as $tabela)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                        <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                    </tr>

                                @endforeach
                                @break

                            @case('05')
                                @foreach ($sl02bdfs05  as $tabela)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                        <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                @break

                            @case('06')
                            @foreach ($sl02bdfs06  as $tabela)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                        <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                @break
                            @case('07')
                            @foreach ($sl02bdfs07  as $tabela)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                        <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                @break

                            @case('08')
                            @foreach ($sl02bdfs08  as $tabela)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                        <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                @break

                            @case('09')
                            @foreach ($sl02bdfs09  as $tabela)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                        <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                @break

                            @case('10')
                            @foreach ($sl02bdfs10  as $tabela)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                        <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                @break

                            @case('11')
                            @foreach ($sl02bdfs11  as $tabela)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                        <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                @break

                            @case('12')
                            @foreach ($sl02bdfs12  as $tabela)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($tabela->dt_movimento)->format('d/m/Y')}}</td>
                                        <td>{{ 'R$'.number_format($tabela->saldo_atual, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->limite, 2, ',', '.') }}</td>
                                        <td>{{ 'R$'.number_format($tabela->diferenca, 2, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                @break
                            @default
                            <tr>
                                <th colspan='4'>Não houve Registros</th>
                            </tr>
                        @endswitch
                </tbody>
                </table>
                <p><span>Total Global </span> {{ isset($total) ?  'R$  '.number_format($total, 2, ',', '.') : '' }}</p>
                </ul>
            @endif
        @endforeach
    @endif
    </div>
@endif
<div id="historico1"></div>
<input type="hidden"  id="totalrisco" value="{{ isset($total) ?  str_replace(',', '', $total) : '' }}">
<input type="hidden"  id="totalfalta" value="0.00">
<input type="hidden"  id="totalsobra" value="0.00">
