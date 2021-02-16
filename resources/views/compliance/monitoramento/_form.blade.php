<div class="input-field col s3">
    <input type="date" id="data" name="data" class="validate upper"
           value="">
</div>

<div class="input-field col s9">
    <select class="slMultiple" multiple="multiple" name="superintendencia[]" id="superintendencia" size="50">
        <option value="" disabled selected>Você pode selecionar várias opções:</option>
        <option value="1">Todas Superintendências</option>
        @foreach($registros as $registro)
            <option value="{{ $registro->se}}">{{ $registro->seDescricao }}</option>
        @endforeach
    </select>
    <label for="superintendencia">Agendamento Para:</label>
</div>


<div class="input-field col s6">
    <select name="tipodeunidade" id="tipodeunidade">
        <option value="" selected>Tipo de Unidade</option>
        @foreach($tiposDeUnidade as $tipoDeUnidade)
            <option value="{{$tipoDeUnidade->id}}">{{ $tipoDeUnidade->tipodescricao }}</option>
        @endforeach
    </select>
    <label for="tipodeunidade">Selecione um tipo de Unidade:</label>
</div>


{{--            @can('inspeçãomonitorada_adicionar')      @endcan--}}

<div class="input-field col s6">
    <button class="btn blue">Processar</button>
</div>
