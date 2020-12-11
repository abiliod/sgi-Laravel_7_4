<div class="input-field col s8">
    <select name="grupoVerificacao_id" id="grupoVerificacao_id" onChange="mostrarDiv()" >
       <option value="" {{(!empty($registro->grupoVerificacao_id) ? 'selected' : '')}}>Selecione um Grupo de Verificação</option>
        @foreach($gruposdeverificacao as $grupodeverificacao)
        <option value="{{ $grupodeverificacao->id }}"
        {{(isset($registro->grupoVerificacao_id)
        && $registro->grupoVerificacao_id == $grupodeverificacao->id
          ? 'selected' : '')}}>
        {{ $grupodeverificacao->ciclo}} / {{ $grupodeverificacao->tipoVerificacao}} / {{$grupodeverificacao->sigla }} / {{$grupodeverificacao->numeroGrupoVerificacao }} - {{$grupodeverificacao->nomegrupo}}
        </option>
        @endforeach
    </select>
	<label>Ciclo/Tipo de Verificação/Tipo de Unidade/Grupo de Verificação</label>
</div>
<div class="input-field col s2">
	<input type="text" name="numeroDoTeste" class="validate" value="{{ isset($registro->numeroDoTeste) ? $registro->numeroDoTeste : '' }}">
	<label>Número do Teste</label>
</div>



<div class="input-field col s2">
    <select name="inspecaoObrigatoria" id="inspecaoObrigatoria" onchange="AtualizarTotalPontos();">
        <option value="79" {{(isset($registro->inspecaoObrigatoria) && $registro->inspecaoObrigatoria == 'Sim'  ? 'selected' : '')}}>Sim</option>
        <option value="0" {{(isset($registro->inspecaoObrigatoria) && $registro->inspecaoObrigatoria == 'Não'  ? 'selected' : '')}}>Não</option>
    </select>
    <label>Inspeção Obrigatória</label>
</div>

<div class="input-field col s12" id="teste">
    <i class="material-icons prefix">mode_edit</i>
    <textarea  id="teste" name="teste" class="materialize-textarea">
    {{ isset($registro->teste) ? $registro->teste : '' }}
    </textarea>
    <label for="teste">Teste:</label>
</div>

<div class="input-field col s12" id="ajuda">
    <i class="material-icons prefix">mode_edit</i>
    <textarea  id="ajuda" name="ajuda" class="materialize-textarea">
    {{ isset($registro->ajuda) ? $registro->ajuda : '' }}
    </textarea>
    <label for="ajuda">Ajuda:</label>
</div>

<div class="input-field col s12" id="amostra">
    <i class="material-icons prefix">mode_edit</i>
    <textarea  id="amostra" name="amostra" class="materialize-textarea">
    {{ isset($registro->amostra) ? $registro->amostra : '' }}
    </textarea>
    <label for="amostra">Amostra:</label>
</div>

<div class="input-field col s12">
	<input type="text" name="norma" class="validate" value="{{ isset($registro->norma) ? $registro->norma : '' }}">
	<label>Norma:</label>
</div>

<div id="CampoOculto" onLoad="mostrarDiv()">
    <div class="input-field col s12">
        <input type="text" name="sappp" class="validate" value="{{ isset($registro->sappp) ? $registro->sappp : '' }}">
        <label>Forma de Avaliar - SAPPP:</label>
    </div>

    <div class="input-field col s12">
        <input type="text" name="tabela_CFP" class="validate" value="{{ isset($registro->tabela_CFP) ? $registro->tabela_CFP : '' }}">
        <label>Tabela de Irregularidades - CFP:</label>
    </div>
</div>

<div class="input-field col s4">
    <select name="impactoFinanceiro" id="impactoFinanceiro" onchange="AtualizarTotalPontos();">
        <option value="9" {{(isset($registro->impactoFinanceiro) && $registro->impactoFinanceiro == 'Sim'  ? 'selected' : '')}}>Sim</option>
        <option value="0" {{(isset($registro->impactoFinanceiro) && $registro->impactoFinanceiro == 'Não'  ? 'selected' : '')}}>Não</option>
    </select>
    <label>Inpacto Financeiro Mensurável Imediato:</label>
</div>
<div class="input-field col s4">
    <select name="riscoFinanceiro"  id="riscoFinanceiro" onchange="AtualizarTotalPontos();">
        <option value="4" {{(isset($registro->riscoFinanceiro) && $registro->riscoFinanceiro == 'Sim'  ? 'selected' : '')}}>Sim</option>
        <option value="0" {{(isset($registro->riscoFinanceiro) && $registro->riscoFinanceiro == 'Não'  ? 'selected' : '')}}>Não</option>
    </select>
	<label>Risco Financeiro Penalização & Multas Contratuais: </label>

</div>
<div class="input-field col s4">
    <select name="descumprimentoLeisContratos"  id="descumprimentoLeisContratos" onchange="AtualizarTotalPontos();">
        <option value="2" {{(isset($registro->descumprimentoLeisContratos) && $registro->descumprimentoLeisContratos == 'Sim'  ? 'selected' : '')}}>Sim</option>
        <option value="0" {{(isset($registro->descumprimentoLeisContratos) && $registro->descumprimentoLeisContratos == 'Não'  ? 'selected' : '')}}>Não</option>
    </select>
	<label>Descumprimento de Lei ou Norma externa:</label>
</div>
<div class="input-field col s3">

    <select name="descumprimentoNormaInterna" id="descumprimentoNormaInterna"  onchange="AtualizarTotalPontos();">
        <option value="1" {{(isset($registro->descumprimentoNormaInterna) && $registro->descumprimentoNormaInterna == 'Sim'  ? 'selected' : '')}}>Sim</option>
        <option value="0" {{(isset($registro->descumprimentoNormaInterna) && $registro->descumprimentoNormaInterna == 'Não'  ? 'selected' : '')}}>Não</option>
    </select>
	<label>Descumprimento de Norma Interna:</label>
</div>

<div class="input-field col s4">
    <select name="riscoSegurancaIntegridade" id="riscoSegurancaIntegridade" onchange="AtualizarTotalPontos();">
        <option value="3" {{(isset($registro->riscoSegurancaIntegridade) && $registro->riscoSegurancaIntegridade == 'Sim'  ? 'selected' : '')}}>Sim</option>
        <option value="0" {{(isset($registro->riscoSegurancaIntegridade) && $registro->riscoSegurancaIntegridade == 'Não'  ? 'selected' : '')}}>Não</option>
    </select>
	<label>Risco de Seg. e Integ. do Patrimônio, Bens e Pessoas:</label>
</div>

<div class="input-field col s3">
    <select name="riscoImgInstitucional" id="riscoImgInstitucional" onchange="AtualizarTotalPontos();">
        <option value="2" {{(isset($registro->riscoImgInstitucional) && $registro->riscoImgInstitucional == 'Sim'  ? 'selected' : '')}}>Sim</option>
        <option value="0" {{(isset($registro->riscoImgInstitucional) && $registro->riscoImgInstitucional == 'Não'  ? 'selected' : '')}}>Não</option>
    </select>
	<label>Risco Direto à Imagen Institucional:</label>
</div>

<div class="input-field col s2" >
  <input type="text" name="totalPontos" id="totalPontos" value="{{ isset($registro->totalPontos) ? $registro->totalPontos : '0' }}" readonly>
	<label class="active">Total de Pontos:</label>
</div>

<div class="input-field col s12" id="roteiroConforme">
    <i class="material-icons prefix">mode_edit</i>
    <textarea  id="roteiroConforme" name="roteiroConforme" class="materialize-textarea">
    {{ isset($registro->roteiroConforme) ? $registro->roteiroConforme : '' }}
    </textarea>
    <label for="roteiroConforme">Roteiro Relato Conforme:</label>
</div>

<div class="input-field col s12" id="roteiroNaoConforme">
    <i class="material-icons prefix">mode_edit</i>
    <textarea  id="roteiroNaoConforme" name="roteiroNaoConforme" class="materialize-textarea">
    {{ isset($registro->roteiroNaoConforme) ? $registro->roteiroNaoConforme : '' }}
    </textarea>
    <label for="roteiroNaoConforme">Roteiro Relato Não Conforme:</label>
</div>

<div class="input-field col s12" id="roteiroNaoVerificado">
    <i class="material-icons prefix">mode_edit</i>
    <textarea  id="roteiroNaoVerificado" name="roteiroNaoVerificado" class="materialize-textarea">
    {{ isset($registro->roteiroNaoVerificado) ? $registro->roteiroNaoVerificado : '' }}
    </textarea>
    <label for="roteiroNaoVerificado">Roteiro Relato Não Verificado:</label>
</div>

<div class="input-field col s12" id="itemanosanteriores">
    <i class="material-icons prefix">mode_edit</i>
    <textarea  id="itemanosanteriores" name="itemanosanteriores" class="materialize-textarea">
    {{ isset($registro->itemanosanteriores) ? $registro->itemanosanteriores : '' }}
    </textarea>
    <label for="itemanosanteriores">Possiveis Itens de Inspeções em Anos Anteriores:</label>
</div>

<div class="input-field col s12">
	<input type="text" name="consequencias" class="validate" value="{{ isset($registro->consequencias) ? $registro->consequencias : '' }}">
	<label>Possíveis Consequências da Situação Encontrada:</label>
</div>

<div class="input-field col s12" id="orientacao">
    <i class="material-icons prefix">mode_edit</i>
            <textarea  id="orientacao" name="orientacao" class="materialize-textarea">
            {{ isset($registro->orientacao) ? $registro->orientacao : '' }}
            </textarea>
        <label for="orientacao">Orientações: </label>
</div>
