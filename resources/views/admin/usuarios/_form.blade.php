<div class="row"> </div>

<div class="row">
    <div class="input-field col s6">
        <select name="activeUser" id="activeUser">
            <option value="1" {{(isset($registro->activeUser) && $registro->activeUser == '1'  ? 'selected' : '')}}>Usuário Ativo</option>
            <option value="0" {{(isset($registro->activeUser) && $registro->activeUser == '0'  ? 'selected' : '')}}>Usuário Inativo</option>

        </select>
        <label for="situacao" >Situação do Usuário</label>
    </div>


    <div class="input-field  col s6">
        <input type="text"  id="name" name="name" class="validade" value="{{ isset($usuario->name) ? $usuario->name : '' }}">
        <label for="name">Nome</label>
    </div>
</div>

<div class="row col s12">
    <div class="input-field col s6">
        <input type="text" name="document" class="validade" value="{{ isset($usuario->document) ? $usuario->document : '' }}">
        <label>Matrícula</label>
    </div>

    <div class="input-field  col s6">
        <input type="text"  id="email"  name="email" class="validade" value="{{ isset($usuario->email) ? $usuario->email : '' }}">
        <label for="email">E-mail</label>
    </div>

</div>

<div class="row col s12">
    <div class="input-field col s6">
        <input type="text" name="businessUnit" class="validade" value="{{ isset($usuario->businessUnit) ? $usuario->businessUnit : '' }}">
        <label>Lotação</label>
    </div>
    <div class="input-field col s6">
                <label>{{ isset($usuario->businessUnit) ? $usuario->businessUnit.' trocar o nr. pela descrição' : 'Unidade não localizada' }}</label>
    </div>

</div>




