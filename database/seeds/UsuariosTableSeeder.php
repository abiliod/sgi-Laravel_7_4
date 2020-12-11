<?php
use Illuminate\Database\Seeder;
use App\User;

class UsuariosTableSeeder extends Seeder{
    /**
    * @return void
    */
    public function run(){

        if(User::where('email','=','admin@sgiweb.com')->count()){
            $usuario = User::where('email','=','admin@sgiweb.com')->first();
            $usuario->name = "Masterkey-Abilio";
            $usuario->document	 = '83288082';
            $usuario->businessUnit	 = '434448';
            $usuario->email = "admin@sgiweb.com";
            $usuario->password = bcrypt("12345678");
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name = "Masterkey-Abilio";
            $usuario->email = "admin@sgiweb.com";
            $usuario->document	 = '83288082';
            $usuario->businessUnit	 = '434448';
            $usuario->password = bcrypt("12345678");
            $usuario->save();
        }


        if(User::where('email','=','admin@compliance.com')->count()){
            $usuario = User::where('email','=','admin@compliance.com')->first();
            $usuario->name = "Masterkey-MarcosV";
            $usuario->email = "admin@compliance.com";
            $usuario->document	 = '89056582';
            $usuario->businessUnit	 = '434063';
            $usuario->password = bcrypt("12345678");
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name = "Masterkey-MarcosV";
            $usuario->email = "admin@compliance.com";
            $usuario->document	 = '89056582';
            $usuario->businessUnit	 = '434063';
            $usuario->password = bcrypt("12345678");
            $usuario->save();
        }

        if(User::where('email','=','abiezerb@correios.com.br')->count()){
            $usuario = User::where('email','=','abiezerb@correios.com.br')->first();
            $usuario->name	 = 'ABIEZER BAZARELLO';
            $usuario->document	 = '85773735';
            $usuario->businessUnit	 = '434446';
            $usuario->email	 = 'abiezerb@correios.com.br';
            $usuario->password = bcrypt('85773735');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ABIEZER BAZARELLO';
            $usuario->document	 = '85773735';
            $usuario->businessUnit	 = '434446';
            $usuario->email	 = 'abiezerb@correios.com.br';
            $usuario->password = bcrypt('85773735');
            $usuario->save();
        }

        if(User::where('email','=','abilio.dias@correios.com.br')->count()){
            $usuario = User::where('email','=','abilio.dias@correios.com.br')->first();
            $usuario->name	 = 'ABILIO DIAS FERREIRA';
            $usuario->document	 = '83288082';
            $usuario->businessUnit	 = '434448';
            $usuario->email	 = 'abilio.dias@correios.com.br';
            $usuario->password = bcrypt('83288082');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ABILIO DIAS FERREIRA';
            $usuario->document	 = '83288082';
            $usuario->businessUnit	 = '434448';
            $usuario->email	 = 'abilio.dias@correios.com.br';
            $usuario->password = bcrypt('83288082');
            $usuario->save();
        }

        if(User::where('email','=','adaorodrigues@correios.com.br')->count()){
            $usuario = User::where('email','=','adaorodrigues@correios.com.br')->first();
            $usuario->name	 = 'ADAO ALVES RODRIGUES';
            $usuario->document	 = '85264121';
            $usuario->businessUnit	 = '434464';
            $usuario->email	 = 'adaorodrigues@correios.com.br';
            $usuario->password = bcrypt('85264121');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ADAO ALVES RODRIGUES';
            $usuario->document	 = '85264121';
            $usuario->businessUnit	 = '434464';
            $usuario->email	 = 'adaorodrigues@correios.com.br';
            $usuario->password = bcrypt('85264121');
            $usuario->save();
        }

        if(User::where('email','=','adersoncezar@correios.com.br')->count()){
            $usuario = User::where('email','=','adersoncezar@correios.com.br')->first();
            $usuario->name	 = 'ANDERSON SILVA CEZAR';
            $usuario->document	 = '84126817';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'adersoncezar@correios.com.br';
            $usuario->password = bcrypt('84126817');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ANDERSON SILVA CEZAR';
            $usuario->document	 = '84126817';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'adersoncezar@correios.com.br';
            $usuario->password = bcrypt('84126817');
            $usuario->save();
        }

        if(User::where('email','=','Adolfo.Olivio@correios.com.br')->count()){
            $usuario = User::where('email','=','Adolfo.Olivio@correios.com.br')->first();
            $usuario->name	 = 'ADOLFO OLIVIO VAREIRA GARCIA';
            $usuario->document	 = '86861875';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'Adolfo.Olivio@correios.com.br';
            $usuario->password = bcrypt('86861875');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ADOLFO OLIVIO VAREIRA GARCIA';
            $usuario->document	 = '86861875';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'Adolfo.Olivio@correios.com.br';
            $usuario->password = bcrypt('86861875');
            $usuario->save();
        }

        if(User::where('email','=','Adrianad@correios.com.br')->count()){
            $usuario = User::where('email','=','Adrianad@correios.com.br')->first();
            $usuario->name	 = 'ADRIANA DA SILVA SANTANA';
            $usuario->document	 = '84284404';
            $usuario->businessUnit	 = '434467';
            $usuario->email	 = 'Adrianad@correios.com.br';
            $usuario->password = bcrypt('84284404');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ADRIANA DA SILVA SANTANA';
            $usuario->document	 = '84284404';
            $usuario->businessUnit	 = '434467';
            $usuario->email	 = 'Adrianad@correios.com.br';
            $usuario->password = bcrypt('84284404');
            $usuario->save();
        }

        if(User::where('email','=','adrianolacerda@correios.com.br')->count()){
            $usuario = User::where('email','=','adrianolacerda@correios.com.br')->first();
            $usuario->name	 = 'ADRIANO JOSE DE LACERDA RODRIGUES';
            $usuario->document	 = '89536398';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'adrianolacerda@correios.com.br';
            $usuario->password = bcrypt('89536398');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ADRIANO JOSE DE LACERDA RODRIGUES';
            $usuario->document	 = '89536398';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'adrianolacerda@correios.com.br';
            $usuario->password = bcrypt('89536398');
            $usuario->save();
        }

        if(User::where('email','=','adrianosoares@correios.com.br')->count()){
            $usuario = User::where('email','=','adrianosoares@correios.com.br')->first();
            $usuario->name	 = 'ADRIANO ROBERTO SOARES';
            $usuario->document	 = '81062117';
            $usuario->businessUnit	 = '434134';
            $usuario->email	 = 'adrianosoares@correios.com.br';
            $usuario->password = bcrypt('81062117');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ADRIANO ROBERTO SOARES';
            $usuario->document	 = '81062117';
            $usuario->businessUnit	 = '434134';
            $usuario->email	 = 'adrianosoares@correios.com.br';
            $usuario->password = bcrypt('81062117');
            $usuario->save();
        }

        if(User::where('email','=','adrianoteza@correios.com.br')->count()){
            $usuario = User::where('email','=','adrianoteza@correios.com.br')->first();
            $usuario->name	 = 'ADRIANO MARTINS TEZA';
            $usuario->document	 = '81071906';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'adrianoteza@correios.com.br';
            $usuario->password = bcrypt('81071906');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ADRIANO MARTINS TEZA';
            $usuario->document	 = '81071906';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'adrianoteza@correios.com.br';
            $usuario->password = bcrypt('81071906');
            $usuario->save();
        }

        if(User::where('email','=','aflauzino@correios.com.br')->count()){
            $usuario = User::where('email','=','aflauzino@correios.com.br')->first();
            $usuario->name	 = 'ANDERSON FLAUZINO INOCENCIO';
            $usuario->document	 = '84120290';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'aflauzino@correios.com.br';
            $usuario->password = bcrypt('84120290');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ANDERSON FLAUZINO INOCENCIO';
            $usuario->document	 = '84120290';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'aflauzino@correios.com.br';
            $usuario->password = bcrypt('84120290');
            $usuario->save();
        }

        if(User::where('email','=','AHHAIBARA@correios.com.br')->count()){
            $usuario = User::where('email','=','AHHAIBARA@correios.com.br')->first();
            $usuario->name	 = 'ANDERSON HOLZBACH HAIBARA';
            $usuario->document	 = '89234804';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'AHHAIBARA@correios.com.br';
            $usuario->password = bcrypt('89234804');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ANDERSON HOLZBACH HAIBARA';
            $usuario->document	 = '89234804';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'AHHAIBARA@correios.com.br';
            $usuario->password = bcrypt('89234804');
            $usuario->save();
        }

        if(User::where('email','=','Albertodantas@correios.com.br')->count()){
            $usuario = User::where('email','=','Albertodantas@correios.com.br')->first();
            $usuario->name	 = 'ALBERTO LUIZ DANTAS JÚNIOR';
            $usuario->document	 = '86273825';
            $usuario->businessUnit	 = '434465';
            $usuario->email	 = 'Albertodantas@correios.com.br';
            $usuario->password = bcrypt('86273825');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ALBERTO LUIZ DANTAS JÚNIOR';
            $usuario->document	 = '86273825';
            $usuario->businessUnit	 = '434465';
            $usuario->email	 = 'Albertodantas@correios.com.br';
            $usuario->password = bcrypt('86273825');
            $usuario->save();
        }

        if(User::where('email','=','ALEDJAIRE@correios.com.br')->count()){
            $usuario = User::where('email','=','ALEDJAIRE@correios.com.br')->first();
            $usuario->name	 = 'FRANCISCO ALEDJAIRE DA SILVA VIEIRA';
            $usuario->document	 = '89017382';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'ALEDJAIRE@correios.com.br';
            $usuario->password = bcrypt('89017382');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'FRANCISCO ALEDJAIRE DA SILVA VIEIRA';
            $usuario->document	 = '89017382';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'ALEDJAIRE@correios.com.br';
            $usuario->password = bcrypt('89017382');
            $usuario->save();
        }

        if(User::where('email','=','ALESSANDRA QUINTINO RODRIGUES')->count()){
            $usuario = User::where('email','=','ALESSANDRA QUINTINO RODRIGUES')->first();
            $usuario->name	 = 'ALESSANDRA QUINTINO RODRIGUES';
            $usuario->document	 = '89571533';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'ALESSANDRA QUINTINO RODRIGUES';
            $usuario->password = bcrypt('89571533');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ALESSANDRA QUINTINO RODRIGUES';
            $usuario->document	 = '89571533';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'ALESSANDRA QUINTINO RODRIGUES';
            $usuario->password = bcrypt('89571533');
            $usuario->save();
        }

        if(User::where('email','=','AlexandreMB@correios.com.br')->count()){
            $usuario = User::where('email','=','AlexandreMB@correios.com.br')->first();
            $usuario->name	 = 'ALEXANDRE MIRANDA BATISTA DA COSTA';
            $usuario->document	 = '84532548';
            $usuario->businessUnit	 = '434449';
            $usuario->email	 = 'AlexandreMB@correios.com.br';
            $usuario->password = bcrypt('84532548');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ALEXANDRE MIRANDA BATISTA DA COSTA';
            $usuario->document	 = '84532548';
            $usuario->businessUnit	 = '434449';
            $usuario->email	 = 'AlexandreMB@correios.com.br';
            $usuario->password = bcrypt('84532548');
            $usuario->save();
        }

        if(User::where('email','=','Alexandro.Ramos@correios.com.br')->count()){
            $usuario = User::where('email','=','Alexandro.Ramos@correios.com.br')->first();
            $usuario->name	 = 'ALEXANDRO RAMOS QUEIROZ';
            $usuario->document	 = '83299165';
            $usuario->businessUnit	 = '434452';
            $usuario->email	 = 'Alexandro.Ramos@correios.com.br';
            $usuario->password = bcrypt('83299165');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ALEXANDRO RAMOS QUEIROZ';
            $usuario->document	 = '83299165';
            $usuario->businessUnit	 = '434452';
            $usuario->email	 = 'Alexandro.Ramos@correios.com.br';
            $usuario->password = bcrypt('83299165');
            $usuario->save();
        }

        if(User::where('email','=','ALEXSGARCIA@correios.com.br')->count()){
            $usuario = User::where('email','=','ALEXSGARCIA@correios.com.br')->first();
            $usuario->name	 = 'ALEX SAMUEL GARCIA';
            $usuario->document	 = '86877860';
            $usuario->businessUnit	 = '434457';
            $usuario->email	 = 'ALEXSGARCIA@correios.com.br';
            $usuario->password = bcrypt('86877860');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ALEX SAMUEL GARCIA';
            $usuario->document	 = '86877860';
            $usuario->businessUnit	 = '434457';
            $usuario->email	 = 'ALEXSGARCIA@correios.com.br';
            $usuario->password = bcrypt('86877860');
            $usuario->save();
        }

        if(User::where('email','=','AliksonO@correios.com.br')->count()){
            $usuario = User::where('email','=','AliksonO@correios.com.br')->first();
            $usuario->name	 = 'ALIKSON DE OLIVEIRA FARIAS';
            $usuario->document	 = '85060739';
            $usuario->businessUnit	 = '434463';
            $usuario->email	 = 'AliksonO@correios.com.br';
            $usuario->password = bcrypt('85060739');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ALIKSON DE OLIVEIRA FARIAS';
            $usuario->document	 = '85060739';
            $usuario->businessUnit	 = '434463';
            $usuario->email	 = 'AliksonO@correios.com.br';
            $usuario->password = bcrypt('85060739');
            $usuario->save();
        }

        if(User::where('email','=','ALSARAGAO@correios.com.br')->count()){
            $usuario = User::where('email','=','ALSARAGAO@correios.com.br')->first();
            $usuario->name	 = 'ANDRÉ LUIZ DA SILVA ARAGÃO';
            $usuario->document	 = '86900862';
            $usuario->businessUnit	 = '434457';
            $usuario->email	 = 'ALSARAGAO@correios.com.br';
            $usuario->password = bcrypt('86900862');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ANDRÉ LUIZ DA SILVA ARAGÃO';
            $usuario->document	 = '86900862';
            $usuario->businessUnit	 = '434457';
            $usuario->email	 = 'ALSARAGAO@correios.com.br';
            $usuario->password = bcrypt('86900862');
            $usuario->save();
        }

        if(User::where('email','=','altairdamasio@correios.com.br')->count()){
            $usuario = User::where('email','=','altairdamasio@correios.com.br')->first();
            $usuario->name	 = 'ALTAIR DAMASIO DE SOUSA';
            $usuario->document	 = '85270040';
            $usuario->businessUnit	 = '434464';
            $usuario->email	 = 'altairdamasio@correios.com.br';
            $usuario->password = bcrypt('85270040');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ALTAIR DAMASIO DE SOUSA';
            $usuario->document	 = '85270040';
            $usuario->businessUnit	 = '434464';
            $usuario->email	 = 'altairdamasio@correios.com.br';
            $usuario->password = bcrypt('85270040');
            $usuario->save();
        }

        if(User::where('email','=','altairsilva@correios.com.br')->count()){
            $usuario = User::where('email','=','altairsilva@correios.com.br')->first();
            $usuario->name	 = 'ALTAIR DONIZETE DA SILVA';
            $usuario->document	 = '89121856';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'altairsilva@correios.com.br';
            $usuario->password = bcrypt('89121856');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ALTAIR DONIZETE DA SILVA';
            $usuario->document	 = '89121856';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'altairsilva@correios.com.br';
            $usuario->password = bcrypt('89121856');
            $usuario->save();
        }

        if(User::where('email','=','Ana.Coracy@correios.com.br')->count()){
            $usuario = User::where('email','=','Ana.Coracy@correios.com.br')->first();
            $usuario->name	 = 'ANA CORACY PEDROSO FERREIRA';
            $usuario->document	 = '83298410';
            $usuario->businessUnit	 = '434448';
            $usuario->email	 = 'Ana.Coracy@correios.com.br';
            $usuario->password = bcrypt('83298410');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ANA CORACY PEDROSO FERREIRA';
            $usuario->document	 = '83298410';
            $usuario->businessUnit	 = '434448';
            $usuario->email	 = 'Ana.Coracy@correios.com.br';
            $usuario->password = bcrypt('83298410');
            $usuario->save();
        }

        if(User::where('email','=','ANACM@CORREIOS.COM.BR')->count()){
            $usuario = User::where('email','=','ANACM@CORREIOS.COM.BR')->first();
            $usuario->name	 = 'ANA CELIA ANDRADE MARIZ';
            $usuario->document	 = '85048178';
            $usuario->businessUnit	 = '434463';
            $usuario->email	 = 'ANACM@CORREIOS.COM.BR';
            $usuario->password = bcrypt('85048178');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ANA CELIA ANDRADE MARIZ';
            $usuario->document	 = '85048178';
            $usuario->businessUnit	 = '434463';
            $usuario->email	 = 'ANACM@CORREIOS.COM.BR';
            $usuario->password = bcrypt('85048178');
            $usuario->save();
        }

        if(User::where('email','=','anadolores@correios.com.br')->count()){
            $usuario = User::where('email','=','anadolores@correios.com.br')->first();
            $usuario->name	 = 'ANA DOLORES GOMES DE OLIVEIRA';
            $usuario->document	 = '80828353';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'anadolores@correios.com.br';
            $usuario->password = bcrypt('80828353');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ANA DOLORES GOMES DE OLIVEIRA';
            $usuario->document	 = '80828353';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'anadolores@correios.com.br';
            $usuario->password = bcrypt('80828353');
            $usuario->save();
        }

        if(User::where('email','=','andersonmesquita@correios.com.br')->count()){
            $usuario = User::where('email','=','andersonmesquita@correios.com.br')->first();
            $usuario->name	 = 'ANDERSON CASELATO DE MESQUITA';
            $usuario->document	 = '84120134';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'andersonmesquita@correios.com.br';
            $usuario->password = bcrypt('84120134');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ANDERSON CASELATO DE MESQUITA';
            $usuario->document	 = '84120134';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'andersonmesquita@correios.com.br';
            $usuario->password = bcrypt('84120134');
            $usuario->save();
        }

        if(User::where('email','=','ANJOS@correios.com.br')->count()){
            $usuario = User::where('email','=','ANJOS@correios.com.br')->first();
            $usuario->name	 = 'MARIA DOS ANJOS DE MELO';
            $usuario->document	 = '85055557';
            $usuario->businessUnit	 = '433410';
            $usuario->email	 = 'ANJOS@correios.com.br';
            $usuario->password = bcrypt('85055557');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MARIA DOS ANJOS DE MELO';
            $usuario->document	 = '85055557';
            $usuario->businessUnit	 = '433410';
            $usuario->email	 = 'ANJOS@correios.com.br';
            $usuario->password = bcrypt('85055557');
            $usuario->save();
        }

        if(User::where('email','=','AnthonyF@correios.com.br')->count()){
            $usuario = User::where('email','=','AnthonyF@correios.com.br')->first();
            $usuario->name	 = 'ANTHONY FREITAS DE LIRA';
            $usuario->document	 = '85069493';
            $usuario->businessUnit	 = '434463';
            $usuario->email	 = 'AnthonyF@correios.com.br';
            $usuario->password = bcrypt('85069493');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ANTHONY FREITAS DE LIRA';
            $usuario->document	 = '85069493';
            $usuario->businessUnit	 = '434463';
            $usuario->email	 = 'AnthonyF@correios.com.br';
            $usuario->password = bcrypt('85069493');
            $usuario->save();
        }

        if(User::where('email','=','Antonio.Cruz@correios.com.br')->count()){
            $usuario = User::where('email','=','Antonio.Cruz@correios.com.br')->first();
            $usuario->name	 = 'ANTONIO FERNANDO DA CRUZ SOUZA';
            $usuario->document	 = '80856659';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'Antonio.Cruz@correios.com.br';
            $usuario->password = bcrypt('80856659');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ANTONIO FERNANDO DA CRUZ SOUZA';
            $usuario->document	 = '80856659';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'Antonio.Cruz@correios.com.br';
            $usuario->password = bcrypt('80856659');
            $usuario->save();
        }

        if(User::where('email','=','Antoniocl@correios.com.br')->count()){
            $usuario = User::where('email','=','Antoniocl@correios.com.br')->first();
            $usuario->name	 = 'ANTONIO COSTA DE LIMA';
            $usuario->document	 = '84531967';
            $usuario->businessUnit	 = '434449';
            $usuario->email	 = 'Antoniocl@correios.com.br';
            $usuario->password = bcrypt('84531967');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ANTONIO COSTA DE LIMA';
            $usuario->document	 = '84531967';
            $usuario->businessUnit	 = '434449';
            $usuario->email	 = 'Antoniocl@correios.com.br';
            $usuario->password = bcrypt('84531967');
            $usuario->save();
        }

        if(User::where('email','=','antoniodorival@correios.com.br')->count()){
            $usuario = User::where('email','=','antoniodorival@correios.com.br')->first();
            $usuario->name	 = 'ANTONIO DORIVAL DE AGUIAR';
            $usuario->document	 = '84086718';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'antoniodorival@correios.com.br';
            $usuario->password = bcrypt('84086718');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ANTONIO DORIVAL DE AGUIAR';
            $usuario->document	 = '84086718';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'antoniodorival@correios.com.br';
            $usuario->password = bcrypt('84086718');
            $usuario->save();
        }

        if(User::where('email','=','ANTONIOMARCELO@correios.com.br')->count()){
            $usuario = User::where('email','=','ANTONIOMARCELO@correios.com.br')->first();
            $usuario->name	 = 'ANTONIO MARCELO BONIFACIO PEREIRA';
            $usuario->document	 = '89050550';
            $usuario->businessUnit	 = '434057';
            $usuario->email	 = 'ANTONIOMARCELO@correios.com.br';
            $usuario->password = bcrypt('89050550');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ANTONIO MARCELO BONIFACIO PEREIRA';
            $usuario->document	 = '89050550';
            $usuario->businessUnit	 = '434057';
            $usuario->email	 = 'ANTONIOMARCELO@correios.com.br';
            $usuario->password = bcrypt('89050550');
            $usuario->save();
        }

        if(User::where('email','=','antoniosnunes@correios.com.br')->count()){
            $usuario = User::where('email','=','antoniosnunes@correios.com.br')->first();
            $usuario->name	 = 'ANTONIO DA SILVA NUNES FILHO';
            $usuario->document	 = '85266418';
            $usuario->businessUnit	 = '434464';
            $usuario->email	 = 'antoniosnunes@correios.com.br';
            $usuario->password = bcrypt('85266418');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ANTONIO DA SILVA NUNES FILHO';
            $usuario->document	 = '85266418';
            $usuario->businessUnit	 = '434464';
            $usuario->email	 = 'antoniosnunes@correios.com.br';
            $usuario->password = bcrypt('85266418');
            $usuario->save();
        }

        if(User::where('email','=','arlan@correios.com.br')->count()){
            $usuario = User::where('email','=','arlan@correios.com.br')->first();
            $usuario->name	 = 'ARLAN PEREIRA DE SOUZA';
            $usuario->document	 = '80128670';
            $usuario->businessUnit	 = '430653';
            $usuario->email	 = 'arlan@correios.com.br';
            $usuario->password = bcrypt('80128670');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ARLAN PEREIRA DE SOUZA';
            $usuario->document	 = '80128670';
            $usuario->businessUnit	 = '430653';
            $usuario->email	 = 'arlan@correios.com.br';
            $usuario->password = bcrypt('80128670');
            $usuario->save();
        }

        if(User::where('email','=','ARNALDOSILVA@correios.com.br')->count()){
            $usuario = User::where('email','=','ARNALDOSILVA@correios.com.br')->first();
            $usuario->name	 = 'ARNALDO DA SILVA SANTOS';
            $usuario->document	 = '89114230';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'ARNALDOSILVA@correios.com.br';
            $usuario->password = bcrypt('89114230');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ARNALDO DA SILVA SANTOS';
            $usuario->document	 = '89114230';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'ARNALDOSILVA@correios.com.br';
            $usuario->password = bcrypt('89114230');
            $usuario->save();
        }

        if(User::where('email','=','arns@correios.com.br')->count()){
            $usuario = User::where('email','=','arns@correios.com.br')->first();
            $usuario->name	 = 'JULIANO ARNS';
            $usuario->document	 = '87080508';
            $usuario->businessUnit	 = '434458';
            $usuario->email	 = 'arns@correios.com.br';
            $usuario->password = bcrypt('87080508');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'JULIANO ARNS';
            $usuario->document	 = '87080508';
            $usuario->businessUnit	 = '434458';
            $usuario->email	 = 'arns@correios.com.br';
            $usuario->password = bcrypt('87080508');
            $usuario->save();
        }

        if(User::where('email','=','ARUFINO@correios.com.br')->count()){
            $usuario = User::where('email','=','ARUFINO@correios.com.br')->first();
            $usuario->name	 = 'ANA LUCIA RUFINO DE SOUZA MARANHO';
            $usuario->document	 = '88762670';
            $usuario->businessUnit	 = '434057';
            $usuario->email	 = 'ARUFINO@correios.com.br';
            $usuario->password = bcrypt('88762670');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ANA LUCIA RUFINO DE SOUZA MARANHO';
            $usuario->document	 = '88762670';
            $usuario->businessUnit	 = '434057';
            $usuario->email	 = 'ARUFINO@correios.com.br';
            $usuario->password = bcrypt('88762670');
            $usuario->save();
        }

        if(User::where('email','=','atamir@correios.com.br')->count()){
            $usuario = User::where('email','=','atamir@correios.com.br')->first();
            $usuario->name	 = 'ATAMIR VILMAR PROCEKE';
            $usuario->document	 = '85652261';
            $usuario->businessUnit	 = '434456';
            $usuario->email	 = 'atamir@correios.com.br';
            $usuario->password = bcrypt('85652261');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ATAMIR VILMAR PROCEKE';
            $usuario->document	 = '85652261';
            $usuario->businessUnit	 = '434456';
            $usuario->email	 = 'atamir@correios.com.br';
            $usuario->password = bcrypt('85652261');
            $usuario->save();
        }

        if(User::where('email','=','Atsilva@correios.com.br')->count()){
            $usuario = User::where('email','=','Atsilva@correios.com.br')->first();
            $usuario->name	 = 'ALESSANDRO TEIXEIRA DA SILVA';
            $usuario->document	 = '82784744';
            $usuario->businessUnit	 = '434454';
            $usuario->email	 = 'Atsilva@correios.com.br';
            $usuario->password = bcrypt('82784744');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ALESSANDRO TEIXEIRA DA SILVA';
            $usuario->document	 = '82784744';
            $usuario->businessUnit	 = '434454';
            $usuario->email	 = 'Atsilva@correios.com.br';
            $usuario->password = bcrypt('82784744');
            $usuario->save();
        }

        if(User::where('email','=','Avale@correios.com.br')->count()){
            $usuario = User::where('email','=','Avale@correios.com.br')->first();
            $usuario->name	 = 'ANTONIO MARCOS DO VALE QUEIROZ';
            $usuario->document	 = '80539858';
            $usuario->businessUnit	 = '434446';
            $usuario->email	 = 'Avale@correios.com.br';
            $usuario->password = bcrypt('80539858');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ANTONIO MARCOS DO VALE QUEIROZ';
            $usuario->document	 = '80539858';
            $usuario->businessUnit	 = '434446';
            $usuario->email	 = 'Avale@correios.com.br';
            $usuario->password = bcrypt('80539858');
            $usuario->save();
        }

        if(User::where('email','=','avania@correios.com.br')->count()){
            $usuario = User::where('email','=','avania@correios.com.br')->first();
            $usuario->name	 = 'AVANI ALMEIDA DE SOUZA';
            $usuario->document	 = '81323190';
            $usuario->businessUnit	 = '434057';
            $usuario->email	 = 'avania@correios.com.br';
            $usuario->password = bcrypt('81323190');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'AVANI ALMEIDA DE SOUZA';
            $usuario->document	 = '81323190';
            $usuario->businessUnit	 = '434057';
            $usuario->email	 = 'avania@correios.com.br';
            $usuario->password = bcrypt('81323190');
            $usuario->save();
        }

        if(User::where('email','=','caiodiego@correios.com.br')->count()){
            $usuario = User::where('email','=','caiodiego@correios.com.br')->first();
            $usuario->name	 = 'CAIO DIEGO MARTINS';
            $usuario->document	 = '89268350';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'caiodiego@correios.com.br';
            $usuario->password = bcrypt('89268350');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'CAIO DIEGO MARTINS';
            $usuario->document	 = '89268350';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'caiodiego@correios.com.br';
            $usuario->password = bcrypt('89268350');
            $usuario->save();
        }

        if(User::where('email','=','carlosolirodrigues@correios.com.br')->count()){
            $usuario = User::where('email','=','carlosolirodrigues@correios.com.br')->first();
            $usuario->name	 = 'CARLOS ALBERTO DE OLIVEIRA RODRIGUES';
            $usuario->document	 = '83235159';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'carlosolirodrigues@correios.com.br';
            $usuario->password = bcrypt('83235159');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'CARLOS ALBERTO DE OLIVEIRA RODRIGUES';
            $usuario->document	 = '83235159';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'carlosolirodrigues@correios.com.br';
            $usuario->password = bcrypt('83235159');
            $usuario->save();
        }

        if(User::where('email','=','casilva@correios.com.br')->count()){
            $usuario = User::where('email','=','casilva@correios.com.br')->first();
            $usuario->name	 = 'CARLOS ANTONIO DA SILVA';
            $usuario->document	 = '80109683';
            $usuario->businessUnit	 = '434454';
            $usuario->email	 = 'casilva@correios.com.br';
            $usuario->password = bcrypt('80109683');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'CARLOS ANTONIO DA SILVA';
            $usuario->document	 = '80109683';
            $usuario->businessUnit	 = '434454';
            $usuario->email	 = 'casilva@correios.com.br';
            $usuario->password = bcrypt('80109683');
            $usuario->save();
        }

        if(User::where('email','=','cinarachagas@correios.com.br')->count()){
            $usuario = User::where('email','=','cinarachagas@correios.com.br')->first();
            $usuario->name	 = 'CINARA CHAGAS PEIXOTO';
            $usuario->document	 = '84084855';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'cinarachagas@correios.com.br';
            $usuario->password = bcrypt('84084855');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'CINARA CHAGAS PEIXOTO';
            $usuario->document	 = '84084855';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'cinarachagas@correios.com.br';
            $usuario->password = bcrypt('84084855');
            $usuario->save();
        }

        if(User::where('email','=','claudemir.yonamine@correios.com.br')->count()){
            $usuario = User::where('email','=','claudemir.yonamine@correios.com.br')->first();
            $usuario->name	 = 'CLAUDEMIR DE OLIVEIRA YONAMINE';
            $usuario->document	 = '82035326';
            $usuario->businessUnit	 = '434468';
            $usuario->email	 = 'claudemir.yonamine@correios.com.br';
            $usuario->password = bcrypt('82035326');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'CLAUDEMIR DE OLIVEIRA YONAMINE';
            $usuario->document	 = '82035326';
            $usuario->businessUnit	 = '434468';
            $usuario->email	 = 'claudemir.yonamine@correios.com.br';
            $usuario->password = bcrypt('82035326');
            $usuario->save();
        }

        if(User::where('email','=','CLAUDIAARAUJO@correios.com.br')->count()){
            $usuario = User::where('email','=','CLAUDIAARAUJO@correios.com.br')->first();
            $usuario->name	 = 'CLAUDIA MARTINS DE OLIVEIRA A E SILVA';
            $usuario->document	 = '81080638';
            $usuario->businessUnit	 = '434467';
            $usuario->email	 = 'CLAUDIAARAUJO@correios.com.br';
            $usuario->password = bcrypt('81080638');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'CLAUDIA MARTINS DE OLIVEIRA A E SILVA';
            $usuario->document	 = '81080638';
            $usuario->businessUnit	 = '434467';
            $usuario->email	 = 'CLAUDIAARAUJO@correios.com.br';
            $usuario->password = bcrypt('81080638');
            $usuario->save();
        }

        if(User::where('email','=','ClaudioLz@correios.com.br')->count()){
            $usuario = User::where('email','=','ClaudioLz@correios.com.br')->first();
            $usuario->name	 = 'CLÁUDIO FERNANDES DA LUZ';
            $usuario->document	 = '84532483';
            $usuario->businessUnit	 = '434449';
            $usuario->email	 = 'ClaudioLz@correios.com.br';
            $usuario->password = bcrypt('84532483');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'CLÁUDIO FERNANDES DA LUZ';
            $usuario->document	 = '84532483';
            $usuario->businessUnit	 = '434449';
            $usuario->email	 = 'ClaudioLz@correios.com.br';
            $usuario->password = bcrypt('84532483');
            $usuario->save();
        }

        if(User::where('email','=','cleoberto@correios.com.br')->count()){
            $usuario = User::where('email','=','cleoberto@correios.com.br')->first();
            $usuario->name	 = 'CLEOBERTO DA SILVA BRANDÃO';
            $usuario->document	 = '80546803';
            $usuario->businessUnit	 = '434446';
            $usuario->email	 = 'cleoberto@correios.com.br';
            $usuario->password = bcrypt('80546803');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'CLEOBERTO DA SILVA BRANDÃO';
            $usuario->document	 = '80546803';
            $usuario->businessUnit	 = '434446';
            $usuario->email	 = 'cleoberto@correios.com.br';
            $usuario->password = bcrypt('80546803');
            $usuario->save();
        }

        if(User::where('email','=','dagmararaujo@correios.com.br')->count()){
            $usuario = User::where('email','=','dagmararaujo@correios.com.br')->first();
            $usuario->name	 = 'DAGMAR ARAUJO DE MORAES';
            $usuario->document	 = '83204270';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'dagmararaujo@correios.com.br';
            $usuario->password = bcrypt('83204270');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'DAGMAR ARAUJO DE MORAES';
            $usuario->document	 = '83204270';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'dagmararaujo@correios.com.br';
            $usuario->password = bcrypt('83204270');
            $usuario->save();
        }

        if(User::where('email','=','daniel.albernaz@correios.com.br')->count()){
            $usuario = User::where('email','=','daniel.albernaz@correios.com.br')->first();
            $usuario->name	 = 'DANIEL MATEUS ALBERNAZ';
            $usuario->document	 = '82032092';
            $usuario->businessUnit	 = '434468';
            $usuario->email	 = 'daniel.albernaz@correios.com.br';
            $usuario->password = bcrypt('82032092');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'DANIEL MATEUS ALBERNAZ';
            $usuario->document	 = '82032092';
            $usuario->businessUnit	 = '434468';
            $usuario->email	 = 'daniel.albernaz@correios.com.br';
            $usuario->password = bcrypt('82032092');
            $usuario->save();
        }

        if(User::where('email','=','danillolemes@correios.com.br')->count()){
            $usuario = User::where('email','=','danillolemes@correios.com.br')->first();
            $usuario->name	 = 'DANILO LEMOS GONÇALVES';
            $usuario->document	 = '80140521';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'danillolemes@correios.com.br';
            $usuario->password = bcrypt('80140521');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'DANILO LEMOS GONÇALVES';
            $usuario->document	 = '80140521';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'danillolemes@correios.com.br';
            $usuario->password = bcrypt('80140521');
            $usuario->save();
        }

        if(User::where('email','=','danilloviana@correios.com.br')->count()){
            $usuario = User::where('email','=','danilloviana@correios.com.br')->first();
            $usuario->name	 = 'DANILLO WEDSON LEITE VIANA';
            $usuario->document	 = '85064238';
            $usuario->businessUnit	 = '434463';
            $usuario->email	 = 'danilloviana@correios.com.br';
            $usuario->password = bcrypt('85064238');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'DANILLO WEDSON LEITE VIANA';
            $usuario->document	 = '85064238';
            $usuario->businessUnit	 = '434463';
            $usuario->email	 = 'danilloviana@correios.com.br';
            $usuario->password = bcrypt('85064238');
            $usuario->save();
        }

        if(User::where('email','=','deniseandrade@correios.com.br')->count()){
            $usuario = User::where('email','=','deniseandrade@correios.com.br')->first();
            $usuario->name	 = 'DENISE RODRIGUES ANDRADE DA SILVA';
            $usuario->document	 = '83238301';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'deniseandrade@correios.com.br';
            $usuario->password = bcrypt('83238301');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'DENISE RODRIGUES ANDRADE DA SILVA';
            $usuario->document	 = '83238301';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'deniseandrade@correios.com.br';
            $usuario->password = bcrypt('83238301');
            $usuario->save();
        }

        if(User::where('email','=','DercileyAlexandre@correios.com.br')->count()){
            $usuario = User::where('email','=','DercileyAlexandre@correios.com.br')->first();
            $usuario->name	 = 'DERCILEY ALEXANDRE PAES DE MELO';
            $usuario->document	 = '80116116';
            $usuario->businessUnit	 = '434467';
            $usuario->email	 = 'DercileyAlexandre@correios.com.br';
            $usuario->password = bcrypt('80116116');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'DERCILEY ALEXANDRE PAES DE MELO';
            $usuario->document	 = '80116116';
            $usuario->businessUnit	 = '434467';
            $usuario->email	 = 'DercileyAlexandre@correios.com.br';
            $usuario->password = bcrypt('80116116');
            $usuario->save();
        }

        if(User::where('email','=','DianaA@correios.com.br')->count()){
            $usuario = User::where('email','=','DianaA@correios.com.br')->first();
            $usuario->name	 = 'DIANA ARCARO';
            $usuario->document	 = '84286547';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'DianaA@correios.com.br';
            $usuario->password = bcrypt('84286547');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'DIANA ARCARO';
            $usuario->document	 = '84286547';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'DianaA@correios.com.br';
            $usuario->password = bcrypt('84286547');
            $usuario->save();
        }

        if(User::where('email','=','Diogo@correios.com.br')->count()){
            $usuario = User::where('email','=','Diogo@correios.com.br')->first();
            $usuario->name	 = 'DIOGO ALEXANDRE MONTEIRO MACHADO';
            $usuario->document	 = '85634891';
            $usuario->businessUnit	 = '434456';
            $usuario->email	 = 'Diogo@correios.com.br';
            $usuario->password = bcrypt('85634891');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'DIOGO ALEXANDRE MONTEIRO MACHADO';
            $usuario->document	 = '85634891';
            $usuario->businessUnit	 = '434456';
            $usuario->email	 = 'Diogo@correios.com.br';
            $usuario->password = bcrypt('85634891');
            $usuario->save();
        }

        if(User::where('email','=','dmv@correios.com.br')->count()){
            $usuario = User::where('email','=','dmv@correios.com.br')->first();
            $usuario->name	 = 'DANIEL MARQUES DE VASCONCELOS';
            $usuario->document	 = '82027757';
            $usuario->businessUnit	 = '434468';
            $usuario->email	 = 'dmv@correios.com.br';
            $usuario->password = bcrypt('82027757');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'DANIEL MARQUES DE VASCONCELOS';
            $usuario->document	 = '82027757';
            $usuario->businessUnit	 = '434468';
            $usuario->email	 = 'dmv@correios.com.br';
            $usuario->password = bcrypt('82027757');
            $usuario->save();
        }

        if(User::where('email','=','EBERAM@correios.com.br')->count()){
            $usuario = User::where('email','=','EBERAM@correios.com.br')->first();
            $usuario->name	 = 'EBER AMARAL PRADO';
            $usuario->document	 = '86853384';
            $usuario->businessUnit	 = '434457';
            $usuario->email	 = 'EBERAM@correios.com.br';
            $usuario->password = bcrypt('86853384');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'EBER AMARAL PRADO';
            $usuario->document	 = '86853384';
            $usuario->businessUnit	 = '434457';
            $usuario->email	 = 'EBERAM@correios.com.br';
            $usuario->password = bcrypt('86853384');
            $usuario->save();
        }

        if(User::where('email','=','ebsantos@correios.com.br')->count()){
            $usuario = User::where('email','=','ebsantos@correios.com.br')->first();
            $usuario->name	 = 'EUNIVALDO BEZERRA DOS SANTOS';
            $usuario->document	 = '89187016';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'ebsantos@correios.com.br';
            $usuario->password = bcrypt('89187016');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'EUNIVALDO BEZERRA DOS SANTOS';
            $usuario->document	 = '89187016';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'ebsantos@correios.com.br';
            $usuario->password = bcrypt('89187016');
            $usuario->save();
        }

        if(User::where('email','=','edemirs@correios.com.br')->count()){
            $usuario = User::where('email','=','edemirs@correios.com.br')->first();
            $usuario->name	 = 'EDEMIR SILVEIRA LEONARDO';
            $usuario->document	 = '81106734';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'edemirs@correios.com.br';
            $usuario->password = bcrypt('81106734');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'EDEMIR SILVEIRA LEONARDO';
            $usuario->document	 = '81106734';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'edemirs@correios.com.br';
            $usuario->password = bcrypt('81106734');
            $usuario->save();
        }

        if(User::where('email','=','EdileneBuriti@correios.com.br')->count()){
            $usuario = User::where('email','=','EdileneBuriti@correios.com.br')->first();
            $usuario->name	 = 'EDILENE DE OLIVEIRA BURITI RAMOS';
            $usuario->document	 = '80837328';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'EdileneBuriti@correios.com.br';
            $usuario->password = bcrypt('80837328');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'EDILENE DE OLIVEIRA BURITI RAMOS';
            $usuario->document	 = '80837328';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'EdileneBuriti@correios.com.br';
            $usuario->password = bcrypt('80837328');
            $usuario->save();
        }

        if(User::where('email','=','EDIMIR@correios.com.br')->count()){
            $usuario = User::where('email','=','EDIMIR@correios.com.br')->first();
            $usuario->name	 = 'EDIMIR BARBOSA MARIZ';
            $usuario->document	 = '85051160';
            $usuario->businessUnit	 = '434401';
            $usuario->email	 = 'EDIMIR@correios.com.br';
            $usuario->password = bcrypt('85051160');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'EDIMIR BARBOSA MARIZ';
            $usuario->document	 = '85051160';
            $usuario->businessUnit	 = '434401';
            $usuario->email	 = 'EDIMIR@correios.com.br';
            $usuario->password = bcrypt('85051160');
            $usuario->save();
        }

        if(User::where('email','=','edsonalves@correios.com.br')->count()){
            $usuario = User::where('email','=','edsonalves@correios.com.br')->first();
            $usuario->name	 = 'EDSON ALVES DE ALMEIDA JUNIOR';
            $usuario->document	 = '80869220';
            $usuario->businessUnit	 = '434454';
            $usuario->email	 = 'edsonalves@correios.com.br';
            $usuario->password = bcrypt('80869220');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'EDSON ALVES DE ALMEIDA JUNIOR';
            $usuario->document	 = '80869220';
            $usuario->businessUnit	 = '434454';
            $usuario->email	 = 'edsonalves@correios.com.br';
            $usuario->password = bcrypt('80869220');
            $usuario->save();
        }

        if(User::where('email','=','edsoncangussu@correios.com.br')->count()){
            $usuario = User::where('email','=','edsoncangussu@correios.com.br')->first();
            $usuario->name	 = 'EDSON CARLOS CANGUSSU RAMOS';
            $usuario->document	 = '84093404';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'edsoncangussu@correios.com.br';
            $usuario->password = bcrypt('84093404');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'EDSON CARLOS CANGUSSU RAMOS';
            $usuario->document	 = '84093404';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'edsoncangussu@correios.com.br';
            $usuario->password = bcrypt('84093404');
            $usuario->save();
        }

        if(User::where('email','=','EDSONMELO@correios.com.br')->count()){
            $usuario = User::where('email','=','EDSONMELO@correios.com.br')->first();
            $usuario->name	 = 'EDSON MELO CUNHA';
            $usuario->document	 = '83773231';
            $usuario->businessUnit	 = '434461';
            $usuario->email	 = 'EDSONMELO@correios.com.br';
            $usuario->password = bcrypt('83773231');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'EDSON MELO CUNHA';
            $usuario->document	 = '83773231';
            $usuario->businessUnit	 = '434461';
            $usuario->email	 = 'EDSONMELO@correios.com.br';
            $usuario->password = bcrypt('83773231');
            $usuario->save();
        }

        if(User::where('email','=','edsonmlemes@correios.com.br')->count()){
            $usuario = User::where('email','=','edsonmlemes@correios.com.br')->first();
            $usuario->name	 = 'EDSON MIRA LEMES';
            $usuario->document	 = '84101288';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'edsonmlemes@correios.com.br';
            $usuario->password = bcrypt('84101288');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'EDSON MIRA LEMES';
            $usuario->document	 = '84101288';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'edsonmlemes@correios.com.br';
            $usuario->password = bcrypt('84101288');
            $usuario->save();
        }

        if(User::where('email','=','Elisabeth@correios.com.br')->count()){
            $usuario = User::where('email','=','Elisabeth@correios.com.br')->first();
            $usuario->name	 = 'ELISABETH APARECIDA LEAL';
            $usuario->document	 = '80852688';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'Elisabeth@correios.com.br';
            $usuario->password = bcrypt('80852688');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ELISABETH APARECIDA LEAL';
            $usuario->document	 = '80852688';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'Elisabeth@correios.com.br';
            $usuario->password = bcrypt('80852688');
            $usuario->save();
        }

        if(User::where('email','=','ELISANGELALVES@correios.com.br')->count()){
            $usuario = User::where('email','=','ELISANGELALVES@correios.com.br')->first();
            $usuario->name	 = 'ELISANGELA ALVES ARAUJO';
            $usuario->document	 = '89122119';
            $usuario->businessUnit	 = '434859';
            $usuario->email	 = 'ELISANGELALVES@correios.com.br';
            $usuario->password = bcrypt('89122119');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ELISANGELA ALVES ARAUJO';
            $usuario->document	 = '89122119';
            $usuario->businessUnit	 = '434859';
            $usuario->email	 = 'ELISANGELALVES@correios.com.br';
            $usuario->password = bcrypt('89122119');
            $usuario->save();
        }

        if(User::where('email','=','elizimar@correios.com.br')->count()){
            $usuario = User::where('email','=','elizimar@correios.com.br')->first();
            $usuario->name	 = 'ELIZIMAR MAIA DE OLIVEIRA';
            $usuario->document	 = '81800185';
            $usuario->businessUnit	 = '434460';
            $usuario->email	 = 'elizimar@correios.com.br';
            $usuario->password = bcrypt('81800185');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ELIZIMAR MAIA DE OLIVEIRA';
            $usuario->document	 = '81800185';
            $usuario->businessUnit	 = '434460';
            $usuario->email	 = 'elizimar@correios.com.br';
            $usuario->password = bcrypt('81800185');
            $usuario->save();
        }

        if(User::where('email','=','EnioJ@correios.com.br')->count()){
            $usuario = User::where('email','=','EnioJ@correios.com.br')->first();
            $usuario->name	 = 'ENIO JACOBSON MENESES DE OLIVEIRA';
            $usuario->document	 = '85053732';
            $usuario->businessUnit	 = '434463';
            $usuario->email	 = 'EnioJ@correios.com.br';
            $usuario->password = bcrypt('85053732');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ENIO JACOBSON MENESES DE OLIVEIRA';
            $usuario->document	 = '85053732';
            $usuario->businessUnit	 = '434463';
            $usuario->email	 = 'EnioJ@correios.com.br';
            $usuario->password = bcrypt('85053732');
            $usuario->save();
        }

        if(User::where('email','=','EricV@correios.com.br')->count()){
            $usuario = User::where('email','=','EricV@correios.com.br')->first();
            $usuario->name	 = 'ERIC VIEIRA DE OLIVEIRA';
            $usuario->document	 = '85066168';
            $usuario->businessUnit	 = '434463';
            $usuario->email	 = 'EricV@correios.com.br';
            $usuario->password = bcrypt('85066168');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ERIC VIEIRA DE OLIVEIRA';
            $usuario->document	 = '85066168';
            $usuario->businessUnit	 = '434463';
            $usuario->email	 = 'EricV@correios.com.br';
            $usuario->password = bcrypt('85066168');
            $usuario->save();
        }

        if(User::where('email','=','ermilton@correios.com.br')->count()){
            $usuario = User::where('email','=','ermilton@correios.com.br')->first();
            $usuario->name	 = 'ANTONIO ERMILTON SOARES DO NASCIMENTO';
            $usuario->document	 = '81784660';
            $usuario->businessUnit	 = '434460';
            $usuario->email	 = 'ermilton@correios.com.br';
            $usuario->password = bcrypt('81784660');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ANTONIO ERMILTON SOARES DO NASCIMENTO';
            $usuario->document	 = '81784660';
            $usuario->businessUnit	 = '434460';
            $usuario->email	 = 'ermilton@correios.com.br';
            $usuario->password = bcrypt('81784660');
            $usuario->save();
        }

        if(User::where('email','=','ESTEVAMDP@correios.com.br')->count()){
            $usuario = User::where('email','=','ESTEVAMDP@correios.com.br')->first();
            $usuario->name	 = 'ESTEVAM DANIEL PEREIRA DA SILVA';
            $usuario->document	 = '89263626';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'ESTEVAMDP@correios.com.br';
            $usuario->password = bcrypt('89263626');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ESTEVAM DANIEL PEREIRA DA SILVA';
            $usuario->document	 = '89263626';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'ESTEVAMDP@correios.com.br';
            $usuario->password = bcrypt('89263626');
            $usuario->save();
        }

        if(User::where('email','=','everaldosouza@correios.com.br')->count()){
            $usuario = User::where('email','=','everaldosouza@correios.com.br')->first();
            $usuario->name	 = 'EVERALDO BENEDITO DE SOUZA CORREA';
            $usuario->document	 = '80528163';
            $usuario->businessUnit	 = '434446';
            $usuario->email	 = 'everaldosouza@correios.com.br';
            $usuario->password = bcrypt('80528163');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'EVERALDO BENEDITO DE SOUZA CORREA';
            $usuario->document	 = '80528163';
            $usuario->businessUnit	 = '434446';
            $usuario->email	 = 'everaldosouza@correios.com.br';
            $usuario->password = bcrypt('80528163');
            $usuario->save();
        }

        if(User::where('email','=','fabianaalmeida@correios.com.br')->count()){
            $usuario = User::where('email','=','fabianaalmeida@correios.com.br')->first();
            $usuario->name	 = 'FABIANA DE ALMEIDA MARTINS';
            $usuario->document	 = '81075600';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'fabianaalmeida@correios.com.br';
            $usuario->password = bcrypt('81075600');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'FABIANA DE ALMEIDA MARTINS';
            $usuario->document	 = '81075600';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'fabianaalmeida@correios.com.br';
            $usuario->password = bcrypt('81075600');
            $usuario->save();
        }

        if(User::where('email','=','fabianamelo@correios.com.br')->count()){
            $usuario = User::where('email','=','fabianamelo@correios.com.br')->first();
            $usuario->name	 = 'FABIANA RIOS MELO';
            $usuario->document	 = '80273637';
            $usuario->businessUnit	 = '434459';
            $usuario->email	 = 'fabianamelo@correios.com.br';
            $usuario->password = bcrypt('80273637');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'FABIANA RIOS MELO';
            $usuario->document	 = '80273637';
            $usuario->businessUnit	 = '434459';
            $usuario->email	 = 'fabianamelo@correios.com.br';
            $usuario->password = bcrypt('80273637');
            $usuario->save();
        }

        if(User::where('email','=','fabiano.gabardo@correios.com.br')->count()){
            $usuario = User::where('email','=','fabiano.gabardo@correios.com.br')->first();
            $usuario->name	 = 'FABIANO DE OLIVEIRA GABARDO';
            $usuario->document	 = '85628182';
            $usuario->businessUnit	 = '434456';
            $usuario->email	 = 'fabiano.gabardo@correios.com.br';
            $usuario->password = bcrypt('85628182');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'FABIANO DE OLIVEIRA GABARDO';
            $usuario->document	 = '85628182';
            $usuario->businessUnit	 = '434456';
            $usuario->email	 = 'fabiano.gabardo@correios.com.br';
            $usuario->password = bcrypt('85628182');
            $usuario->save();
        }

        if(User::where('email','=','felix.junior@correios.com.br')->count()){
            $usuario = User::where('email','=','felix.junior@correios.com.br')->first();
            $usuario->name	 = 'FELIX GOLUBIEWSKI JUNIOR';
            $usuario->document	 = '85604372';
            $usuario->businessUnit	 = '434456';
            $usuario->email	 = 'felix.junior@correios.com.br';
            $usuario->password = bcrypt('85604372');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'FELIX GOLUBIEWSKI JUNIOR';
            $usuario->document	 = '85604372';
            $usuario->businessUnit	 = '434456';
            $usuario->email	 = 'felix.junior@correios.com.br';
            $usuario->password = bcrypt('85604372');
            $usuario->save();
        }

        if(User::where('email','=','FERNANDACEMIN@correios.com.br')->count()){
            $usuario = User::where('email','=','FERNANDACEMIN@correios.com.br')->first();
            $usuario->name	 = 'FERNANDA CEMIN';
            $usuario->document	 = '86905511';
            $usuario->businessUnit	 = '434457';
            $usuario->email	 = 'FERNANDACEMIN@correios.com.br';
            $usuario->password = bcrypt('86905511');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'FERNANDA CEMIN';
            $usuario->document	 = '86905511';
            $usuario->businessUnit	 = '434457';
            $usuario->email	 = 'FERNANDACEMIN@correios.com.br';
            $usuario->password = bcrypt('86905511');
            $usuario->save();
        }

        if(User::where('email','=','FERNANDASG@correios.com.br')->count()){
            $usuario = User::where('email','=','FERNANDASG@correios.com.br')->first();
            $usuario->name	 = 'FERNANDA DA SILVA GOMES';
            $usuario->document	 = '89271351';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'FERNANDASG@correios.com.br';
            $usuario->password = bcrypt('89271351');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'FERNANDA DA SILVA GOMES';
            $usuario->document	 = '89271351';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'FERNANDASG@correios.com.br';
            $usuario->password = bcrypt('89271351');
            $usuario->save();
        }

        if(User::where('email','=','fernandodiasrosa@correios.com.br')->count()){
            $usuario = User::where('email','=','fernandodiasrosa@correios.com.br')->first();
            $usuario->name	 = 'FERNANDO ANTONIO DIAS ROSA';
            $usuario->document	 = '84078979';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'fernandodiasrosa@correios.com.br';
            $usuario->password = bcrypt('84078979');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'FERNANDO ANTONIO DIAS ROSA';
            $usuario->document	 = '84078979';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'fernandodiasrosa@correios.com.br';
            $usuario->password = bcrypt('84078979');
            $usuario->save();
        }

        if(User::where('email','=','FERNANDOLSILVA@correios.com.br')->count()){
            $usuario = User::where('email','=','FERNANDOLSILVA@correios.com.br')->first();
            $usuario->name	 = 'FERNANDO LOPES DA SILVA';
            $usuario->document	 = '89241789';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'FERNANDOLSILVA@correios.com.br';
            $usuario->password = bcrypt('89241789');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'FERNANDO LOPES DA SILVA';
            $usuario->document	 = '89241789';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'FERNANDOLSILVA@correios.com.br';
            $usuario->password = bcrypt('89241789');
            $usuario->save();
        }

        if(User::where('email','=','flaviocampos@correios.com.br')->count()){
            $usuario = User::where('email','=','flaviocampos@correios.com.br')->first();
            $usuario->name	 = 'FLÁVIO CAMPOS DAMASCENO';
            $usuario->document	 = '89560124';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'flaviocampos@correios.com.br';
            $usuario->password = bcrypt('89560124');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'FLÁVIO CAMPOS DAMASCENO';
            $usuario->document	 = '89560124';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'flaviocampos@correios.com.br';
            $usuario->password = bcrypt('89560124');
            $usuario->save();
        }

        if(User::where('email','=','flaviomachado@correios.com.br')->count()){
            $usuario = User::where('email','=','flaviomachado@correios.com.br')->first();
            $usuario->name	 = 'FLAVIO PAULO MEIRELLES MACHADO';
            $usuario->document	 = '80142249';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'flaviomachado@correios.com.br';
            $usuario->password = bcrypt('80142249');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'FLAVIO PAULO MEIRELLES MACHADO';
            $usuario->document	 = '80142249';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'flaviomachado@correios.com.br';
            $usuario->password = bcrypt('80142249');
            $usuario->save();
        }

        if(User::where('email','=','FrancineideSa@correios.com.br')->count()){
            $usuario = User::where('email','=','FrancineideSa@correios.com.br')->first();
            $usuario->name	 = 'FRANCINEIDE DA SILVA ALMEIDA';
            $usuario->document	 = '83780734';
            $usuario->businessUnit	 = '434464';
            $usuario->email	 = 'FrancineideSa@correios.com.br';
            $usuario->password = bcrypt('83780734');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'FRANCINEIDE DA SILVA ALMEIDA';
            $usuario->document	 = '83780734';
            $usuario->businessUnit	 = '434464';
            $usuario->email	 = 'FrancineideSa@correios.com.br';
            $usuario->password = bcrypt('83780734');
            $usuario->save();
        }

        if(User::where('email','=','FranciscoIthamarS@correios.com.br')->count()){
            $usuario = User::where('email','=','FranciscoIthamarS@correios.com.br')->first();
            $usuario->name	 = 'FRANCISCO ITHAMAR SANTOS DE SOUZA';
            $usuario->document	 = '84283130';
            $usuario->businessUnit	 = '434450';
            $usuario->email	 = 'FranciscoIthamarS@correios.com.br';
            $usuario->password = bcrypt('84283130');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'FRANCISCO ITHAMAR SANTOS DE SOUZA';
            $usuario->document	 = '84283130';
            $usuario->businessUnit	 = '434450';
            $usuario->email	 = 'FranciscoIthamarS@correios.com.br';
            $usuario->password = bcrypt('84283130');
            $usuario->save();
        }

        if(User::where('email','=','francisconeres@correios.com.br')->count()){
            $usuario = User::where('email','=','francisconeres@correios.com.br')->first();
            $usuario->name	 = 'FRANCISCO NERES DE ARRUDA';
            $usuario->document	 = '81336691';
            $usuario->businessUnit	 = '434447';
            $usuario->email	 = 'francisconeres@correios.com.br';
            $usuario->password = bcrypt('81336691');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'FRANCISCO NERES DE ARRUDA';
            $usuario->document	 = '81336691';
            $usuario->businessUnit	 = '434447';
            $usuario->email	 = 'francisconeres@correios.com.br';
            $usuario->password = bcrypt('81336691');
            $usuario->save();
        }

        if(User::where('email','=','fsobrinho@correios.com.br')->count()){
            $usuario = User::where('email','=','fsobrinho@correios.com.br')->first();
            $usuario->name	 = 'FERNANDO LUIZ DA SILVA SOBRINHO';
            $usuario->document	 = '84133376';
            $usuario->businessUnit	 = '434060';
            $usuario->email	 = 'fsobrinho@correios.com.br';
            $usuario->password = bcrypt('84133376');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'FERNANDO LUIZ DA SILVA SOBRINHO';
            $usuario->document	 = '84133376';
            $usuario->businessUnit	 = '434060';
            $usuario->email	 = 'fsobrinho@correios.com.br';
            $usuario->password = bcrypt('84133376');
            $usuario->save();
        }

        if(User::where('email','=','gerlandio@correios.com.br')->count()){
            $usuario = User::where('email','=','gerlandio@correios.com.br')->first();
            $usuario->name	 = 'FRANKLIN GERLANDIO LIMA PEREIRA';
            $usuario->document	 = '80275150';
            $usuario->businessUnit	 = '434459';
            $usuario->email	 = 'gerlandio@correios.com.br';
            $usuario->password = bcrypt('80275150');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'FRANKLIN GERLANDIO LIMA PEREIRA';
            $usuario->document	 = '80275150';
            $usuario->businessUnit	 = '434459';
            $usuario->email	 = 'gerlandio@correios.com.br';
            $usuario->password = bcrypt('80275150');
            $usuario->save();
        }

        if(User::where('email','=','germanadw@correios.com.br')->count()){
            $usuario = User::where('email','=','germanadw@correios.com.br')->first();
            $usuario->name	 = 'GERMANA DANTAS WANDERLEY';
            $usuario->document	 = '84786671';
            $usuario->businessUnit	 = '434462';
            $usuario->email	 = 'germanadw@correios.com.br';
            $usuario->password = bcrypt('84786671');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'GERMANA DANTAS WANDERLEY';
            $usuario->document	 = '84786671';
            $usuario->businessUnit	 = '434462';
            $usuario->email	 = 'germanadw@correios.com.br';
            $usuario->password = bcrypt('84786671');
            $usuario->save();
        }

        if(User::where('email','=','gilvanm@correios.com.br')->count()){
            $usuario = User::where('email','=','gilvanm@correios.com.br')->first();
            $usuario->name	 = 'GILVAN MALAQUIAS DOS SANTOS';
            $usuario->document	 = '85051071';
            $usuario->businessUnit	 = '434060';
            $usuario->email	 = 'gilvanm@correios.com.br';
            $usuario->password = bcrypt('85051071');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'GILVAN MALAQUIAS DOS SANTOS';
            $usuario->document	 = '85051071';
            $usuario->businessUnit	 = '434060';
            $usuario->email	 = 'gilvanm@correios.com.br';
            $usuario->password = bcrypt('85051071');
            $usuario->save();
        }

        if(User::where('email','=','GIOVANISILVA@correios.com.br')->count()){
            $usuario = User::where('email','=','GIOVANISILVA@correios.com.br')->first();
            $usuario->name	 = 'GIOVANI DE SOUZA SILVA';
            $usuario->document	 = '89198280';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'GIOVANISILVA@correios.com.br';
            $usuario->password = bcrypt('89198280');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'GIOVANI DE SOUZA SILVA';
            $usuario->document	 = '89198280';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'GIOVANISILVA@correios.com.br';
            $usuario->password = bcrypt('89198280');
            $usuario->save();
        }

        if(User::where('email','=','guilhermeaugusto@correios.com.br')->count()){
            $usuario = User::where('email','=','guilhermeaugusto@correios.com.br')->first();
            $usuario->name	 = 'GUILHERME AUGUSTO DE VASCONCELLOS CHAGAS';
            $usuario->document	 = '89571584';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'guilhermeaugusto@correios.com.br';
            $usuario->password = bcrypt('89571584');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'GUILHERME AUGUSTO DE VASCONCELLOS CHAGAS';
            $usuario->document	 = '89571584';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'guilhermeaugusto@correios.com.br';
            $usuario->password = bcrypt('89571584');
            $usuario->save();
        }

        if(User::where('email','=','guilhermebr@correios.com.br')->count()){
            $usuario = User::where('email','=','guilhermebr@correios.com.br')->first();
            $usuario->name	 = 'GUILHERME BESSA RIBEIRO';
            $usuario->document	 = '87074532';
            $usuario->businessUnit	 = '434458';
            $usuario->email	 = 'guilhermebr@correios.com.br';
            $usuario->password = bcrypt('87074532');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'GUILHERME BESSA RIBEIRO';
            $usuario->document	 = '87074532';
            $usuario->businessUnit	 = '434458';
            $usuario->email	 = 'guilhermebr@correios.com.br';
            $usuario->password = bcrypt('87074532');
            $usuario->save();
        }

        if(User::where('email','=','helioherminio@correios.com.br')->count()){
            $usuario = User::where('email','=','helioherminio@correios.com.br')->first();
            $usuario->name	 = 'HÉLIO HERMÍNIO DA SILVA';
            $usuario->document	 = '84769742';
            $usuario->businessUnit	 = '434462';
            $usuario->email	 = 'helioherminio@correios.com.br';
            $usuario->password = bcrypt('84769742');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'HÉLIO HERMÍNIO DA SILVA';
            $usuario->document	 = '84769742';
            $usuario->businessUnit	 = '434462';
            $usuario->email	 = 'helioherminio@correios.com.br';
            $usuario->password = bcrypt('84769742');
            $usuario->save();
        }

        if(User::where('email','=','henriquecesar@correios.com.br')->count()){
            $usuario = User::where('email','=','henriquecesar@correios.com.br')->first();
            $usuario->name	 = 'HENRIQUE CESAR REZENDE E SOUZA';
            $usuario->document	 = '89581474';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'henriquecesar@correios.com.br';
            $usuario->password = bcrypt('89581474');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'HENRIQUE CESAR REZENDE E SOUZA';
            $usuario->document	 = '89581474';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'henriquecesar@correios.com.br';
            $usuario->password = bcrypt('89581474');
            $usuario->save();
        }

        if(User::where('email','=','HENRIQUEG@correios.com.br')->count()){
            $usuario = User::where('email','=','HENRIQUEG@correios.com.br')->first();
            $usuario->name	 = 'CLAUDIO HENRIQUE GONCALVES';
            $usuario->document	 = '85050580';
            $usuario->businessUnit	 = '434463';
            $usuario->email	 = 'HENRIQUEG@correios.com.br';
            $usuario->password = bcrypt('85050580');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'CLAUDIO HENRIQUE GONCALVES';
            $usuario->document	 = '85050580';
            $usuario->businessUnit	 = '434463';
            $usuario->email	 = 'HENRIQUEG@correios.com.br';
            $usuario->password = bcrypt('85050580');
            $usuario->save();
        }

        if(User::where('email','=','Hilljhones@correios.com.br')->count()){
            $usuario = User::where('email','=','Hilljhones@correios.com.br')->first();
            $usuario->name	 = 'HILL JHONE FERREIRA DA SILVA';
            $usuario->document	 = '85776033';
            $usuario->businessUnit	 = '434450';
            $usuario->email	 = 'Hilljhones@correios.com.br';
            $usuario->password = bcrypt('85776033');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'HILL JHONE FERREIRA DA SILVA';
            $usuario->document	 = '85776033';
            $usuario->businessUnit	 = '434450';
            $usuario->email	 = 'Hilljhones@correios.com.br';
            $usuario->password = bcrypt('85776033');
            $usuario->save();
        }

        if(User::where('email','=','iderlan@correios.com.br')->count()){
            $usuario = User::where('email','=','iderlan@correios.com.br')->first();
            $usuario->name	 = 'IDERLAN TEIXEIRA LIMA';
            $usuario->document	 = '83776559';
            $usuario->businessUnit	 = '434461';
            $usuario->email	 = 'iderlan@correios.com.br';
            $usuario->password = bcrypt('83776559');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'IDERLAN TEIXEIRA LIMA';
            $usuario->document	 = '83776559';
            $usuario->businessUnit	 = '434461';
            $usuario->email	 = 'iderlan@correios.com.br';
            $usuario->password = bcrypt('83776559');
            $usuario->save();
        }

        if(User::where('email','=','isacdias@correios.com.br')->count()){
            $usuario = User::where('email','=','isacdias@correios.com.br')->first();
            $usuario->name	 = 'ISAC ALVES DIAS';
            $usuario->document	 = '83279482';
            $usuario->businessUnit	 = '434057';
            $usuario->email	 = 'isacdias@correios.com.br';
            $usuario->password = bcrypt('83279482');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ISAC ALVES DIAS';
            $usuario->document	 = '83279482';
            $usuario->businessUnit	 = '434057';
            $usuario->email	 = 'isacdias@correios.com.br';
            $usuario->password = bcrypt('83279482');
            $usuario->save();
        }

        if(User::where('email','=','ivonetesantos@correios.com.br')->count()){
            $usuario = User::where('email','=','ivonetesantos@correios.com.br')->first();
            $usuario->name	 = 'IVONETE ARCILIOS DOS SANTOS';
            $usuario->document	 = '85599263';
            $usuario->businessUnit	 = '434456';
            $usuario->email	 = 'ivonetesantos@correios.com.br';
            $usuario->password = bcrypt('85599263');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'IVONETE ARCILIOS DOS SANTOS';
            $usuario->document	 = '85599263';
            $usuario->businessUnit	 = '434456';
            $usuario->email	 = 'ivonetesantos@correios.com.br';
            $usuario->password = bcrypt('85599263');
            $usuario->save();
        }

        if(User::where('email','=','jaciaracarvalho@correios.com.br')->count()){
            $usuario = User::where('email','=','jaciaracarvalho@correios.com.br')->first();
            $usuario->name	 = 'JACIARA CARVALHO';
            $usuario->document	 = '85652172';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'jaciaracarvalho@correios.com.br';
            $usuario->password = bcrypt('85652172');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'JACIARA CARVALHO';
            $usuario->document	 = '85652172';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'jaciaracarvalho@correios.com.br';
            $usuario->password = bcrypt('85652172');
            $usuario->save();
        }

        if(User::where('email','=','jadir@correios.com.br')->count()){
            $usuario = User::where('email','=','jadir@correios.com.br')->first();
            $usuario->name	 = 'JADIR DE JESUS GOMES FILHO';
            $usuario->document	 = '81317310';
            $usuario->businessUnit	 = '434447';
            $usuario->email	 = 'jadir@correios.com.br';
            $usuario->password = bcrypt('81317310');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'JADIR DE JESUS GOMES FILHO';
            $usuario->document	 = '81317310';
            $usuario->businessUnit	 = '434447';
            $usuario->email	 = 'jadir@correios.com.br';
            $usuario->password = bcrypt('81317310');
            $usuario->save();
        }

        if(User::where('email','=','jaimeva@correios.com.br')->count()){
            $usuario = User::where('email','=','jaimeva@correios.com.br')->first();
            $usuario->name	 = 'JAIME VELASQUES AZEVEDO';
            $usuario->document	 = '85779580';
            $usuario->businessUnit	 = '434450';
            $usuario->email	 = 'jaimeva@correios.com.br';
            $usuario->password = bcrypt('85779580');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'JAIME VELASQUES AZEVEDO';
            $usuario->document	 = '85779580';
            $usuario->businessUnit	 = '434450';
            $usuario->email	 = 'jaimeva@correios.com.br';
            $usuario->password = bcrypt('85779580');
            $usuario->save();
        }

        if(User::where('email','=','jeanmfranco@correios.com.br')->count()){
            $usuario = User::where('email','=','jeanmfranco@correios.com.br')->first();
            $usuario->name	 = 'JEAN MARCEL FRANCO';
            $usuario->document	 = '86908170';
            $usuario->businessUnit	 = '434457';
            $usuario->email	 = 'jeanmfranco@correios.com.br';
            $usuario->password = bcrypt('86908170');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'JEAN MARCEL FRANCO';
            $usuario->document	 = '86908170';
            $usuario->businessUnit	 = '434457';
            $usuario->email	 = 'jeanmfranco@correios.com.br';
            $usuario->password = bcrypt('86908170');
            $usuario->save();
        }

        if(User::where('email','=','jfarezin@correios.com.br')->count()){
            $usuario = User::where('email','=','jfarezin@correios.com.br')->first();
            $usuario->name	 = 'JORDANA FAREZIN';
            $usuario->document	 = '87082128';
            $usuario->businessUnit	 = '434458';
            $usuario->email	 = 'jfarezin@correios.com.br';
            $usuario->password = bcrypt('87082128');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'JORDANA FAREZIN';
            $usuario->document	 = '87082128';
            $usuario->businessUnit	 = '434458';
            $usuario->email	 = 'jfarezin@correios.com.br';
            $usuario->password = bcrypt('87082128');
            $usuario->save();
        }

        if(User::where('email','=','jlgrandini@correios.com.br')->count()){
            $usuario = User::where('email','=','jlgrandini@correios.com.br')->first();
            $usuario->name	 = 'JOAO LUIS TEIXEIRA GRANDINI';
            $usuario->document	 = '81093934';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'jlgrandini@correios.com.br';
            $usuario->password = bcrypt('81093934');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'JOAO LUIS TEIXEIRA GRANDINI';
            $usuario->document	 = '81093934';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'jlgrandini@correios.com.br';
            $usuario->password = bcrypt('81093934');
            $usuario->save();
        }

        if(User::where('email','=','joaobmc@correios.com.br')->count()){
            $usuario = User::where('email','=','joaobmc@correios.com.br')->first();
            $usuario->name	 = 'JOAO BATISTA MARTINS DA CONCEICAO';
            $usuario->document	 = '83774467';
            $usuario->businessUnit	 = '434461';
            $usuario->email	 = 'joaobmc@correios.com.br';
            $usuario->password = bcrypt('83774467');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'JOAO BATISTA MARTINS DA CONCEICAO';
            $usuario->document	 = '83774467';
            $usuario->businessUnit	 = '434461';
            $usuario->email	 = 'joaobmc@correios.com.br';
            $usuario->password = bcrypt('83774467');
            $usuario->save();
        }

        if(User::where('email','=','JoaoECosta@correios.com.br')->count()){
            $usuario = User::where('email','=','JoaoECosta@correios.com.br')->first();
            $usuario->name	 = 'JOÃO EVANGELISTA BATISTA FRÓES';
            $usuario->document	 = '80833098';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'JoaoECosta@correios.com.br';
            $usuario->password = bcrypt('80833098');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'JOÃO EVANGELISTA BATISTA FRÓES';
            $usuario->document	 = '80833098';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'JoaoECosta@correios.com.br';
            $usuario->password = bcrypt('80833098');
            $usuario->save();
        }

        if(User::where('email','=','JohnAlex@correios.com.br')->count()){
            $usuario = User::where('email','=','JohnAlex@correios.com.br')->first();
            $usuario->name	 = 'JOHN ALEX MELO DE OLIVEIRA';
            $usuario->document	 = '86273264';
            $usuario->businessUnit	 = '434465';
            $usuario->email	 = 'JohnAlex@correios.com.br';
            $usuario->password = bcrypt('86273264');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'JOHN ALEX MELO DE OLIVEIRA';
            $usuario->document	 = '86273264';
            $usuario->businessUnit	 = '434465';
            $usuario->email	 = 'JohnAlex@correios.com.br';
            $usuario->password = bcrypt('86273264');
            $usuario->save();
        }

        if(User::where('email','=','JORGE.ANTONIO@correios.com.br')->count()){
            $usuario = User::where('email','=','JORGE.ANTONIO@correios.com.br')->first();
            $usuario->name	 = 'JORGE ANTONIO DOS SANTOS';
            $usuario->document	 = '83282840';
            $usuario->businessUnit	 = '434448';
            $usuario->email	 = 'JORGE.ANTONIO@correios.com.br';
            $usuario->password = bcrypt('83282840');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'JORGE ANTONIO DOS SANTOS';
            $usuario->document	 = '83282840';
            $usuario->businessUnit	 = '434448';
            $usuario->email	 = 'JORGE.ANTONIO@correios.com.br';
            $usuario->password = bcrypt('83282840');
            $usuario->save();
        }

        if(User::where('email','=','josefjunior@correios.com.br')->count()){
            $usuario = User::where('email','=','josefjunior@correios.com.br')->first();
            $usuario->name	 = 'JOSE FERREIRA COELHO JUNIOR';
            $usuario->document	 = '84086815';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'josefjunior@correios.com.br';
            $usuario->password = bcrypt('84086815');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'JOSE FERREIRA COELHO JUNIOR';
            $usuario->document	 = '84086815';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'josefjunior@correios.com.br';
            $usuario->password = bcrypt('84086815');
            $usuario->save();
        }

        if(User::where('email','=','josiasoliveira@correios.com.br')->count()){
            $usuario = User::where('email','=','josiasoliveira@correios.com.br')->first();
            $usuario->name	 = 'JOSIAS NOGUEIRA DE OLIVEIRA';
            $usuario->document	 = '88829871';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'josiasoliveira@correios.com.br';
            $usuario->password = bcrypt('88829871');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'JOSIAS NOGUEIRA DE OLIVEIRA';
            $usuario->document	 = '88829871';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'josiasoliveira@correios.com.br';
            $usuario->password = bcrypt('88829871');
            $usuario->save();
        }

        if(User::where('email','=','jsalomaof@correios.com.br')->count()){
            $usuario = User::where('email','=','jsalomaof@correios.com.br')->first();
            $usuario->name	 = 'JOAO SALOMAO FILHO';
            $usuario->document	 = '82032084';
            $usuario->businessUnit	 = '434468';
            $usuario->email	 = 'jsalomaof@correios.com.br';
            $usuario->password = bcrypt('82032084');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'JOAO SALOMAO FILHO';
            $usuario->document	 = '82032084';
            $usuario->businessUnit	 = '434468';
            $usuario->email	 = 'jsalomaof@correios.com.br';
            $usuario->password = bcrypt('82032084');
            $usuario->save();
        }

        if(User::where('email','=','julianadasilva@correios.com.br')->count()){
            $usuario = User::where('email','=','julianadasilva@correios.com.br')->first();
            $usuario->name	 = 'JULIANA FERREIRA DA SILVA';
            $usuario->document	 = '84165618';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'julianadasilva@correios.com.br';
            $usuario->password = bcrypt('84165618');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'JULIANA FERREIRA DA SILVA';
            $usuario->document	 = '84165618';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'julianadasilva@correios.com.br';
            $usuario->password = bcrypt('84165618');
            $usuario->save();
        }

        if(User::where('email','=','ASNAMBA@correios.com.br')->count()){
            $usuario = User::where('email','=','ASNAMBA@correios.com.br')->first();
            $usuario->name	 = 'ADEMIR SHIZUO NAMBA';
            $usuario->document	 = '89201752';
            $usuario->businessUnit	 = '434859';
            $usuario->email	 = 'ASNAMBA@correios.com.br';
            $usuario->password = bcrypt('89201752');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ADEMIR SHIZUO NAMBA';
            $usuario->document	 = '89201752';
            $usuario->businessUnit	 = '434859';
            $usuario->email	 = 'ASNAMBA@correios.com.br';
            $usuario->password = bcrypt('89201752');
            $usuario->save();
        }

        if(User::where('email','=','juliomarx@correios.com.br')->count()){
            $usuario = User::where('email','=','juliomarx@correios.com.br')->first();
            $usuario->name	 = 'JULIO CESAR MARQUES DE SOUZA';
            $usuario->document	 = '89207661';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'juliomarx@correios.com.br';
            $usuario->password = bcrypt('89207661');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'JULIO CESAR MARQUES DE SOUZA';
            $usuario->document	 = '89207661';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'juliomarx@correios.com.br';
            $usuario->password = bcrypt('89207661');
            $usuario->save();
        }

        if(User::where('email','=','karenbatista@correios.com.br')->count()){
            $usuario = User::where('email','=','karenbatista@correios.com.br')->first();
            $usuario->name	 = 'KAREN BATISTA MAGALHAES';
            $usuario->document	 = '89552652';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'karenbatista@correios.com.br';
            $usuario->password = bcrypt('89552652');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'KAREN BATISTA MAGALHAES';
            $usuario->document	 = '89552652';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'karenbatista@correios.com.br';
            $usuario->password = bcrypt('89552652');
            $usuario->save();
        }

        if(User::where('email','=','katianeg@correios.com.br')->count()){
            $usuario = User::where('email','=','katianeg@correios.com.br')->first();
            $usuario->name	 = 'KATIANE GONCALVES RABELO';
            $usuario->document	 = '84152362';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'katianeg@correios.com.br';
            $usuario->password = bcrypt('84152362');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'KATIANE GONCALVES RABELO';
            $usuario->document	 = '84152362';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'katianeg@correios.com.br';
            $usuario->password = bcrypt('84152362');
            $usuario->save();
        }

        if(User::where('email','=','KRKODAMA@correios.com.br')->count()){
            $usuario = User::where('email','=','KRKODAMA@correios.com.br')->first();
            $usuario->name	 = 'KENZO RICARDO KODAMA';
            $usuario->document	 = '89189051';
            $usuario->businessUnit	 = '434859';
            $usuario->email	 = 'KRKODAMA@correios.com.br';
            $usuario->password = bcrypt('89189051');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'KENZO RICARDO KODAMA';
            $usuario->document	 = '89189051';
            $usuario->businessUnit	 = '434859';
            $usuario->email	 = 'KRKODAMA@correios.com.br';
            $usuario->password = bcrypt('89189051');
            $usuario->save();
        }

        if(User::where('email','=','leanderson.silva@correios.com.br')->count()){
            $usuario = User::where('email','=','leanderson.silva@correios.com.br')->first();
            $usuario->name	 = 'LEANDERSON LUIS FRANCOLIN DA SILVA';
            $usuario->document	 = '85646024';
            $usuario->businessUnit	 = '434456';
            $usuario->email	 = 'leanderson.silva@correios.com.br';
            $usuario->password = bcrypt('85646024');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'LEANDERSON LUIS FRANCOLIN DA SILVA';
            $usuario->document	 = '85646024';
            $usuario->businessUnit	 = '434456';
            $usuario->email	 = 'leanderson.silva@correios.com.br';
            $usuario->password = bcrypt('85646024');
            $usuario->save();
        }

        if(User::where('email','=','lelisveiga@correios.com.br')->count()){
            $usuario = User::where('email','=','lelisveiga@correios.com.br')->first();
            $usuario->name	 = 'LELIS PEREIRA DA VEIGA';
            $usuario->document	 = '84095458';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'lelisveiga@correios.com.br';
            $usuario->password = bcrypt('84095458');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'LELIS PEREIRA DA VEIGA';
            $usuario->document	 = '84095458';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'lelisveiga@correios.com.br';
            $usuario->password = bcrypt('84095458');
            $usuario->save();
        }

        if(User::where('email','=','LEONESSA@correios.com.br')->count()){
            $usuario = User::where('email','=','LEONESSA@correios.com.br')->first();
            $usuario->name	 = 'CESAR LEONESSA TALMACS';
            $usuario->document	 = '89281101';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'LEONESSA@correios.com.br';
            $usuario->password = bcrypt('89281101');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'CESAR LEONESSA TALMACS';
            $usuario->document	 = '89281101';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'LEONESSA@correios.com.br';
            $usuario->password = bcrypt('89281101');
            $usuario->save();
        }

        if(User::where('email','=','lidianesoares@correios.com.br')->count()){
            $usuario = User::where('email','=','lidianesoares@correios.com.br')->first();
            $usuario->name	 = 'LIDIANE DA COSTA SOARES DOS SANTOS';
            $usuario->document	 = '89584090';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'lidianesoares@correios.com.br';
            $usuario->password = bcrypt('89584090');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'LIDIANE DA COSTA SOARES DOS SANTOS';
            $usuario->document	 = '89584090';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'lidianesoares@correios.com.br';
            $usuario->password = bcrypt('89584090');
            $usuario->save();
        }

        if(User::where('email','=','lourdesmoreira@correios.com.br')->count()){
            $usuario = User::where('email','=','lourdesmoreira@correios.com.br')->first();
            $usuario->name	 = 'MARIA DE LOURDES R. MOREIRA SANT´ANNA';
            $usuario->document	 = '83205730';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'lourdesmoreira@correios.com.br';
            $usuario->password = bcrypt('83205730');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MARIA DE LOURDES R. MOREIRA SANT´ANNA';
            $usuario->document	 = '83205730';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'lourdesmoreira@correios.com.br';
            $usuario->password = bcrypt('83205730');
            $usuario->save();
        }

        if(User::where('email','=','LUANDAGURGEL@correios.com.br')->count()){
            $usuario = User::where('email','=','LUANDAGURGEL@correios.com.br')->first();
            $usuario->name	 = 'LUANDA AMARAL GURGEL DE OLIVEIRA';
            $usuario->document	 = '89337670';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'LUANDAGURGEL@correios.com.br';
            $usuario->password = bcrypt('89337670');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'LUANDA AMARAL GURGEL DE OLIVEIRA';
            $usuario->document	 = '89337670';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'LUANDAGURGEL@correios.com.br';
            $usuario->password = bcrypt('89337670');
            $usuario->save();
        }

        if(User::where('email','=','Lucaspereira@correios.com.br')->count()){
            $usuario = User::where('email','=','Lucaspereira@correios.com.br')->first();
            $usuario->name	 = 'LUCAS GILCEU PEREIRA';
            $usuario->document	 = '86862839';
            $usuario->businessUnit	 = '434457';
            $usuario->email	 = 'Lucaspereira@correios.com.br';
            $usuario->password = bcrypt('86862839');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'LUCAS GILCEU PEREIRA';
            $usuario->document	 = '86862839';
            $usuario->businessUnit	 = '434457';
            $usuario->email	 = 'Lucaspereira@correios.com.br';
            $usuario->password = bcrypt('86862839');
            $usuario->save();
        }

        if(User::where('email','=','lucianaferreirar@correios.com.br')->count()){
            $usuario = User::where('email','=','lucianaferreirar@correios.com.br')->first();
            $usuario->name	 = 'LUCIANA FERREIRA RAYMUNDO';
            $usuario->document	 = '89094158';
            $usuario->businessUnit	 = '430699';
            $usuario->email	 = 'lucianaferreirar@correios.com.br';
            $usuario->password = bcrypt('89094158');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'LUCIANA FERREIRA RAYMUNDO';
            $usuario->document	 = '89094158';
            $usuario->businessUnit	 = '430699';
            $usuario->email	 = 'lucianaferreirar@correios.com.br';
            $usuario->password = bcrypt('89094158');
            $usuario->save();
        }

        if(User::where('email','=','lucianak@correios.com.br')->count()){
            $usuario = User::where('email','=','lucianak@correios.com.br')->first();
            $usuario->name	 = 'LUCIANA KOBE DE OLIVEIRA';
            $usuario->document	 = '87073331';
            $usuario->businessUnit	 = '434458';
            $usuario->email	 = 'lucianak@correios.com.br';
            $usuario->password = bcrypt('87073331');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'LUCIANA KOBE DE OLIVEIRA';
            $usuario->document	 = '87073331';
            $usuario->businessUnit	 = '434458';
            $usuario->email	 = 'lucianak@correios.com.br';
            $usuario->password = bcrypt('87073331');
            $usuario->save();
        }

        if(User::where('email','=','luciano.correa@correios.com.br')->count()){
            $usuario = User::where('email','=','luciano.correa@correios.com.br')->first();
            $usuario->name	 = 'LUCIANO MENDONCA CORREA';
            $usuario->document	 = '82037167';
            $usuario->businessUnit	 = '434468';
            $usuario->email	 = 'luciano.correa@correios.com.br';
            $usuario->password = bcrypt('82037167');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'LUCIANO MENDONCA CORREA';
            $usuario->document	 = '82037167';
            $usuario->businessUnit	 = '434468';
            $usuario->email	 = 'luciano.correa@correios.com.br';
            $usuario->password = bcrypt('82037167');
            $usuario->save();
        }

        if(User::where('email','=','LUCIANOASSIS@correios.com.br')->count()){
            $usuario = User::where('email','=','LUCIANOASSIS@correios.com.br')->first();
            $usuario->name	 = 'LUCIANO RODRIGUES DE ASSIS';
            $usuario->document	 = '89153430';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'LUCIANOASSIS@correios.com.br';
            $usuario->password = bcrypt('89153430');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'LUCIANO RODRIGUES DE ASSIS';
            $usuario->document	 = '89153430';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'LUCIANOASSIS@correios.com.br';
            $usuario->password = bcrypt('89153430');
            $usuario->save();
        }

        if(User::where('email','=','lucivalt@correios.com.br')->count()){
            $usuario = User::where('email','=','lucivalt@correios.com.br')->first();
            $usuario->name	 = 'LUCIVAL DA SILVA TACK';
            $usuario->document	 = '84543612';
            $usuario->businessUnit	 = '434449';
            $usuario->email	 = 'lucivalt@correios.com.br';
            $usuario->password = bcrypt('84543612');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'LUCIVAL DA SILVA TACK';
            $usuario->document	 = '84543612';
            $usuario->businessUnit	 = '434449';
            $usuario->email	 = 'lucivalt@correios.com.br';
            $usuario->password = bcrypt('84543612');
            $usuario->save();
        }

        if(User::where('email','=','luziasilva@correios.com.br')->count()){
            $usuario = User::where('email','=','luziasilva@correios.com.br')->first();
            $usuario->name	 = 'LUZIA DA SILVA BARBOSA';
            $usuario->document	 = '81318928';
            $usuario->businessUnit	 = '434057';
            $usuario->email	 = 'luziasilva@correios.com.br';
            $usuario->password = bcrypt('81318928');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'LUZIA DA SILVA BARBOSA';
            $usuario->document	 = '81318928';
            $usuario->businessUnit	 = '434057';
            $usuario->email	 = 'luziasilva@correios.com.br';
            $usuario->password = bcrypt('81318928');
            $usuario->save();
        }

        if(User::where('email','=','mairlonmorais@correios.com.br')->count()){
            $usuario = User::where('email','=','mairlonmorais@correios.com.br')->first();
            $usuario->name	 = 'MAIRLON DE MORAIS JUNIOR';
            $usuario->document	 = '83778845';
            $usuario->businessUnit	 = '434461';
            $usuario->email	 = 'mairlonmorais@correios.com.br';
            $usuario->password = bcrypt('83778845');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MAIRLON DE MORAIS JUNIOR';
            $usuario->document	 = '83778845';
            $usuario->businessUnit	 = '434461';
            $usuario->email	 = 'mairlonmorais@correios.com.br';
            $usuario->password = bcrypt('83778845');
            $usuario->save();
        }

        if(User::where('email','=','MarceloFerreira@correios.com.br')->count()){
            $usuario = User::where('email','=','MarceloFerreira@correios.com.br')->first();
            $usuario->name	 = 'MARCELO BITTENCOURT FERREIRA';
            $usuario->document	 = '80859992';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'MarceloFerreira@correios.com.br';
            $usuario->password = bcrypt('80859992');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MARCELO BITTENCOURT FERREIRA';
            $usuario->document	 = '80859992';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'MarceloFerreira@correios.com.br';
            $usuario->password = bcrypt('80859992');
            $usuario->save();
        }

        if(User::where('email','=','marcelonaliati@correios.com.br')->count()){
            $usuario = User::where('email','=','marcelonaliati@correios.com.br')->first();
            $usuario->name	 = 'MARCELO MARTINS NALIATI';
            $usuario->document	 = '81096933';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'marcelonaliati@correios.com.br';
            $usuario->password = bcrypt('81096933');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MARCELO MARTINS NALIATI';
            $usuario->document	 = '81096933';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'marcelonaliati@correios.com.br';
            $usuario->password = bcrypt('81096933');
            $usuario->save();
        }

        if(User::where('email','=','marciacavalcante@correios.com.br')->count()){
            $usuario = User::where('email','=','marciacavalcante@correios.com.br')->first();
            $usuario->name	 = 'MÁRCIA VALÉRIA LINS CAVALCANTE CARNAUBA';
            $usuario->document	 = '80273645';
            $usuario->businessUnit	 = '434459';
            $usuario->email	 = 'marciacavalcante@correios.com.br';
            $usuario->password = bcrypt('80273645');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MÁRCIA VALÉRIA LINS CAVALCANTE CARNAUBA';
            $usuario->document	 = '80273645';
            $usuario->businessUnit	 = '434459';
            $usuario->email	 = 'marciacavalcante@correios.com.br';
            $usuario->password = bcrypt('80273645');
            $usuario->save();
        }

        if(User::where('email','=','marciasousa@correios.com.br')->count()){
            $usuario = User::where('email','=','marciasousa@correios.com.br')->first();
            $usuario->name	 = 'MÁRCIA DO SOCORRO DE SOUSA LIMA';
            $usuario->document	 = '84547448';
            $usuario->businessUnit	 = '434449';
            $usuario->email	 = 'marciasousa@correios.com.br';
            $usuario->password = bcrypt('84547448');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MÁRCIA DO SOCORRO DE SOUSA LIMA';
            $usuario->document	 = '84547448';
            $usuario->businessUnit	 = '434449';
            $usuario->email	 = 'marciasousa@correios.com.br';
            $usuario->password = bcrypt('84547448');
            $usuario->save();
        }

        if(User::where('email','=','marcioloureiro@correios.com.br')->count()){
            $usuario = User::where('email','=','marcioloureiro@correios.com.br')->first();
            $usuario->name	 = 'MARCIO AFONSO LOUREIRO';
            $usuario->document	 = '84100737';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'marcioloureiro@correios.com.br';
            $usuario->password = bcrypt('84100737');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MARCIO AFONSO LOUREIRO';
            $usuario->document	 = '84100737';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'marcioloureiro@correios.com.br';
            $usuario->password = bcrypt('84100737');
            $usuario->save();
        }

        if(User::where('email','=','marcosbatista@correios.com.br')->count()){
            $usuario = User::where('email','=','marcosbatista@correios.com.br')->first();
            $usuario->name	 = 'MARCOS BATISTA DOS SANTOS';
            $usuario->document	 = '80843140';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'marcosbatista@correios.com.br';
            $usuario->password = bcrypt('80843140');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MARCOS BATISTA DOS SANTOS';
            $usuario->document	 = '80843140';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'marcosbatista@correios.com.br';
            $usuario->password = bcrypt('80843140');
            $usuario->save();
        }

        if(User::where('email','=','marcoscarvalho@correios.com.br')->count()){
            $usuario = User::where('email','=','marcoscarvalho@correios.com.br')->first();
            $usuario->name	 = 'MARCOS JOSE DE CARVALHO MARTINS SANTOS';
            $usuario->document	 = '81066414';
            $usuario->businessUnit	 = '434464';
            $usuario->email	 = 'marcoscarvalho@correios.com.br';
            $usuario->password = bcrypt('81066414');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MARCOS JOSE DE CARVALHO MARTINS SANTOS';
            $usuario->document	 = '81066414';
            $usuario->businessUnit	 = '434464';
            $usuario->email	 = 'marcoscarvalho@correios.com.br';
            $usuario->password = bcrypt('81066414');
            $usuario->save();
        }

        if(User::where('email','=','marcosmiranda@correios.com.br')->count()){
            $usuario = User::where('email','=','marcosmiranda@correios.com.br')->first();
            $usuario->name	 = 'MARCOS PAULO COELHO MIRANDA';
            $usuario->document	 = '89535286';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'marcosmiranda@correios.com.br';
            $usuario->password = bcrypt('89535286');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MARCOS PAULO COELHO MIRANDA';
            $usuario->document	 = '89535286';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'marcosmiranda@correios.com.br';
            $usuario->password = bcrypt('89535286');
            $usuario->save();
        }

        if(User::where('email','=','mariacristianen@correios.com.br')->count()){
            $usuario = User::where('email','=','mariacristianen@correios.com.br')->first();
            $usuario->name	 = 'MARIA CRISTIANE NASCIMENTO DE LIMA';
            $usuario->document	 = '80136222';
            $usuario->businessUnit	 = '999999';
            $usuario->email	 = 'mariacristianen@correios.com.br';
            $usuario->password = bcrypt('80136222');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MARIA CRISTIANE NASCIMENTO DE LIMA';
            $usuario->document	 = '80136222';
            $usuario->businessUnit	 = '999999';
            $usuario->email	 = 'mariacristianen@correios.com.br';
            $usuario->password = bcrypt('80136222');
            $usuario->save();
        }

        if(User::where('email','=','mariamartinatto@correios.com.br')->count()){
            $usuario = User::where('email','=','mariamartinatto@correios.com.br')->first();
            $usuario->name	 = 'MARIA MARTINATTO';
            $usuario->document	 = '85628867';
            $usuario->businessUnit	 = '434456';
            $usuario->email	 = 'mariamartinatto@correios.com.br';
            $usuario->password = bcrypt('85628867');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MARIA MARTINATTO';
            $usuario->document	 = '85628867';
            $usuario->businessUnit	 = '434456';
            $usuario->email	 = 'mariamartinatto@correios.com.br';
            $usuario->password = bcrypt('85628867');
            $usuario->save();
        }

        if(User::where('email','=','MariaMeirelles2@correios.com.br')->count()){
            $usuario = User::where('email','=','MariaMeirelles2@correios.com.br')->first();
            $usuario->name	 = 'MARIA DE SOUZA MEIRELLES';
            $usuario->document	 = '80121381';
            $usuario->businessUnit	 = '434465';
            $usuario->email	 = 'MariaMeirelles2@correios.com.br';
            $usuario->password = bcrypt('80121381');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MARIA DE SOUZA MEIRELLES';
            $usuario->document	 = '80121381';
            $usuario->businessUnit	 = '434465';
            $usuario->email	 = 'MariaMeirelles2@correios.com.br';
            $usuario->password = bcrypt('80121381');
            $usuario->save();
        }

        if(User::where('email','=','mariar@correios.com.br')->count()){
            $usuario = User::where('email','=','mariar@correios.com.br')->first();
            $usuario->name	 = 'MARIA ROSANE CARNEIRO PEREIRA';
            $usuario->document	 = '85773425';
            $usuario->businessUnit	 = '434450';
            $usuario->email	 = 'mariar@correios.com.br';
            $usuario->password = bcrypt('85773425');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MARIA ROSANE CARNEIRO PEREIRA';
            $usuario->document	 = '85773425';
            $usuario->businessUnit	 = '434450';
            $usuario->email	 = 'mariar@correios.com.br';
            $usuario->password = bcrypt('85773425');
            $usuario->save();
        }

        if(User::where('email','=','marselff2@correios.com.br')->count()){
            $usuario = User::where('email','=','marselff2@correios.com.br')->first();
            $usuario->name	 = 'MARCELO SELLE WOLFF';
            $usuario->document	 = '86892517';
            $usuario->businessUnit	 = '434448';
            $usuario->email	 = 'marselff2@correios.com.br';
            $usuario->password = bcrypt('86892517');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MARCELO SELLE WOLFF';
            $usuario->document	 = '86892517';
            $usuario->businessUnit	 = '434448';
            $usuario->email	 = 'marselff2@correios.com.br';
            $usuario->password = bcrypt('86892517');
            $usuario->save();
        }

        if(User::where('email','=','mauricioalves@correios.com.br')->count()){
            $usuario = User::where('email','=','mauricioalves@correios.com.br')->first();
            $usuario->name	 = 'MAURICIO ALVES DA FONSECA';
            $usuario->document	 = '84161833';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'mauricioalves@correios.com.br';
            $usuario->password = bcrypt('84161833');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MAURICIO ALVES DA FONSECA';
            $usuario->document	 = '84161833';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'mauricioalves@correios.com.br';
            $usuario->password = bcrypt('84161833');
            $usuario->save();
        }

        if(User::where('email','=','mauroborges@correios.com.br')->count()){
            $usuario = User::where('email','=','mauroborges@correios.com.br')->first();
            $usuario->name	 = 'MAURO BORGES';
            $usuario->document	 = '87512793';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'mauroborges@correios.com.br';
            $usuario->password = bcrypt('87512793');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MAURO BORGES';
            $usuario->document	 = '87512793';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'mauroborges@correios.com.br';
            $usuario->password = bcrypt('87512793');
            $usuario->save();
        }

        if(User::where('email','=','maurochaves@correios.com.br')->count()){
            $usuario = User::where('email','=','maurochaves@correios.com.br')->first();
            $usuario->name	 = 'MAURO ANTONIO CHAVES';
            $usuario->document	 = '84092424';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'maurochaves@correios.com.br';
            $usuario->password = bcrypt('84092424');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MAURO ANTONIO CHAVES';
            $usuario->document	 = '84092424';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'maurochaves@correios.com.br';
            $usuario->password = bcrypt('84092424');
            $usuario->save();
        }

        if(User::where('email','=','michelesantos@correios.com.br')->count()){
            $usuario = User::where('email','=','michelesantos@correios.com.br')->first();
            $usuario->name	 = 'MICHELE APARECIDA SANTOS';
            $usuario->document	 = '81096690';
            $usuario->businessUnit	 = '434064';
            $usuario->email	 = 'michelesantos@correios.com.br';
            $usuario->password = bcrypt('81096690');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MICHELE APARECIDA SANTOS';
            $usuario->document	 = '81096690';
            $usuario->businessUnit	 = '434064';
            $usuario->email	 = 'michelesantos@correios.com.br';
            $usuario->password = bcrypt('81096690');
            $usuario->save();
        }

        if(User::where('email','=','Moises@correios.com.br')->count()){
            $usuario = User::where('email','=','Moises@correios.com.br')->first();
            $usuario->name	 = 'MOISES CABRAL SOUSA';
            $usuario->document	 = '80835252';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'Moises@correios.com.br';
            $usuario->password = bcrypt('80835252');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MOISES CABRAL SOUSA';
            $usuario->document	 = '80835252';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'Moises@correios.com.br';
            $usuario->password = bcrypt('80835252');
            $usuario->save();
        }

        if(User::where('email','=','monoelsombra@correios.com.br')->count()){
            $usuario = User::where('email','=','monoelsombra@correios.com.br')->first();
            $usuario->name	 = 'MANOEL ADALBERTO DOS SANTOS SOMBRA';
            $usuario->document	 = '84532564';
            $usuario->businessUnit	 = '434449';
            $usuario->email	 = 'monoelsombra@correios.com.br';
            $usuario->password = bcrypt('84532564');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MANOEL ADALBERTO DOS SANTOS SOMBRA';
            $usuario->document	 = '84532564';
            $usuario->businessUnit	 = '434449';
            $usuario->email	 = 'monoelsombra@correios.com.br';
            $usuario->password = bcrypt('84532564');
            $usuario->save();
        }

        if(User::where('email','=','mqgomes@correios.com.br')->count()){
            $usuario = User::where('email','=','mqgomes@correios.com.br')->first();
            $usuario->name	 = 'MARCIOVANY QUIRINO GOMES';
            $usuario->document	 = '80547265';
            $usuario->businessUnit	 = '434446';
            $usuario->email	 = 'mqgomes@correios.com.br';
            $usuario->password = bcrypt('80547265');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MARCIOVANY QUIRINO GOMES';
            $usuario->document	 = '80547265';
            $usuario->businessUnit	 = '434446';
            $usuario->email	 = 'mqgomes@correios.com.br';
            $usuario->password = bcrypt('80547265');
            $usuario->save();
        }

        if(User::where('email','=','MSERGIO@correios.com.br')->count()){
            $usuario = User::where('email','=','MSERGIO@correios.com.br')->first();
            $usuario->name	 = 'MAURO SERGIO DA SILVA';
            $usuario->document	 = '89143108';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'MSERGIO@correios.com.br';
            $usuario->password = bcrypt('89143108');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MAURO SERGIO DA SILVA';
            $usuario->document	 = '89143108';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'MSERGIO@correios.com.br';
            $usuario->password = bcrypt('89143108');
            $usuario->save();
        }

        if(User::where('email','=','NATANAELPR@correios.com.br')->count()){
            $usuario = User::where('email','=','NATANAELPR@correios.com.br')->first();
            $usuario->name	 = 'NATANAEL PLACIDO RODRIGUES';
            $usuario->document	 = '89271270';
            $usuario->businessUnit	 = '434859';
            $usuario->email	 = 'NATANAELPR@correios.com.br';
            $usuario->password = bcrypt('89271270');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'NATANAEL PLACIDO RODRIGUES';
            $usuario->document	 = '89271270';
            $usuario->businessUnit	 = '434859';
            $usuario->email	 = 'NATANAELPR@correios.com.br';
            $usuario->password = bcrypt('89271270');
            $usuario->save();
        }

        if(User::where('email','=','nelsonsoares@correios.com.br')->count()){
            $usuario = User::where('email','=','nelsonsoares@correios.com.br')->first();
            $usuario->name	 = 'NELSON RODRIGUES SOARES FILHO';
            $usuario->document	 = '83281657';
            $usuario->businessUnit	 = '434055';
            $usuario->email	 = 'nelsonsoares@correios.com.br';
            $usuario->password = bcrypt('83281657');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'NELSON RODRIGUES SOARES FILHO';
            $usuario->document	 = '83281657';
            $usuario->businessUnit	 = '434055';
            $usuario->email	 = 'nelsonsoares@correios.com.br';
            $usuario->password = bcrypt('83281657');
            $usuario->save();
        }

        if(User::where('email','=','neymarlage@correios.com.br')->count()){
            $usuario = User::where('email','=','neymarlage@correios.com.br')->first();
            $usuario->name	 = 'HOLNEYMAR SEBASTIAO LAGE';
            $usuario->document	 = '84140267';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'neymarlage@correios.com.br';
            $usuario->password = bcrypt('84140267');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'HOLNEYMAR SEBASTIAO LAGE';
            $usuario->document	 = '84140267';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'neymarlage@correios.com.br';
            $usuario->password = bcrypt('84140267');
            $usuario->save();
        }

        if(User::where('email','=','Nodel@correios.com.br')->count()){
            $usuario = User::where('email','=','Nodel@correios.com.br')->first();
            $usuario->name	 = 'NODEL DA LUZ FILHO';
            $usuario->document	 = '84274301';
            $usuario->businessUnit	 = '434467';
            $usuario->email	 = 'Nodel@correios.com.br';
            $usuario->password = bcrypt('84274301');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'NODEL DA LUZ FILHO';
            $usuario->document	 = '84274301';
            $usuario->businessUnit	 = '434467';
            $usuario->email	 = 'Nodel@correios.com.br';
            $usuario->password = bcrypt('84274301');
            $usuario->save();
        }

        if(User::where('email','=','oberdan@correios.com.br')->count()){
            $usuario = User::where('email','=','oberdan@correios.com.br')->first();
            $usuario->name	 = 'OBERDAN COSTA GABRIEL DE OLIVIERA';
            $usuario->document	 = '80855113';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'oberdan@correios.com.br';
            $usuario->password = bcrypt('80855113');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'OBERDAN COSTA GABRIEL DE OLIVIERA';
            $usuario->document	 = '80855113';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'oberdan@correios.com.br';
            $usuario->password = bcrypt('80855113');
            $usuario->save();
        }

        if(User::where('email','=','OTAFIM@correios.com.br')->count()){
            $usuario = User::where('email','=','OTAFIM@correios.com.br')->first();
            $usuario->name	 = 'OTAVIO AUGUSTO AZEVEDO BOMFIM';
            $usuario->document	 = '89238257';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'OTAFIM@correios.com.br';
            $usuario->password = bcrypt('89238257');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'OTAVIO AUGUSTO AZEVEDO BOMFIM';
            $usuario->document	 = '89238257';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'OTAFIM@correios.com.br';
            $usuario->password = bcrypt('89238257');
            $usuario->save();
        }

        if(User::where('email','=','otoik@correios.com.br')->count()){
            $usuario = User::where('email','=','otoik@correios.com.br')->first();
            $usuario->name	 = 'KIOTO ODAGUIRI ENES';
            $usuario->document	 = '80116418';
            $usuario->businessUnit	 = '434059';
            $usuario->email	 = 'otoik@correios.com.br';
            $usuario->password = bcrypt('80116418');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'KIOTO ODAGUIRI ENES';
            $usuario->document	 = '80116418';
            $usuario->businessUnit	 = '434059';
            $usuario->email	 = 'otoik@correios.com.br';
            $usuario->password = bcrypt('80116418');
            $usuario->save();
        }

        if(User::where('email','=','patriciaprates@correios.com.br')->count()){
            $usuario = User::where('email','=','patriciaprates@correios.com.br')->first();
            $usuario->name	 = 'PATRICIA DEMOLY PRATES';
            $usuario->document	 = '86905422';
            $usuario->businessUnit	 = '434457';
            $usuario->email	 = 'patriciaprates@correios.com.br';
            $usuario->password = bcrypt('86905422');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'PATRICIA DEMOLY PRATES';
            $usuario->document	 = '86905422';
            $usuario->businessUnit	 = '434457';
            $usuario->email	 = 'patriciaprates@correios.com.br';
            $usuario->password = bcrypt('86905422');
            $usuario->save();
        }

        if(User::where('email','=','PaulaB@correios.com.br')->count()){
            $usuario = User::where('email','=','PaulaB@correios.com.br')->first();
            $usuario->name	 = 'ANA PAULA ADORNO BASTOS';
            $usuario->document	 = '80862926';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'PaulaB@correios.com.br';
            $usuario->password = bcrypt('80862926');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ANA PAULA ADORNO BASTOS';
            $usuario->document	 = '80862926';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'PaulaB@correios.com.br';
            $usuario->password = bcrypt('80862926');
            $usuario->save();
        }

        if(User::where('email','=','paulav@correios.com.br')->count()){
            $usuario = User::where('email','=','paulav@correios.com.br')->first();
            $usuario->name	 = 'PAULA VANESSA DOS SANTOS LINS';
            $usuario->document	 = '80273475';
            $usuario->businessUnit	 = '434459';
            $usuario->email	 = 'paulav@correios.com.br';
            $usuario->password = bcrypt('80273475');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'PAULA VANESSA DOS SANTOS LINS';
            $usuario->document	 = '80273475';
            $usuario->businessUnit	 = '434459';
            $usuario->email	 = 'paulav@correios.com.br';
            $usuario->password = bcrypt('80273475');
            $usuario->save();
        }

        if(User::where('email','=','pauloarruda@correios.com.br')->count()){
            $usuario = User::where('email','=','pauloarruda@correios.com.br')->first();
            $usuario->name	 = 'PAULO MACHADO ARRUDA';
            $usuario->document	 = '84278870';
            $usuario->businessUnit	 = '434467';
            $usuario->email	 = 'pauloarruda@correios.com.br';
            $usuario->password = bcrypt('84278870');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'PAULO MACHADO ARRUDA';
            $usuario->document	 = '84278870';
            $usuario->businessUnit	 = '434467';
            $usuario->email	 = 'pauloarruda@correios.com.br';
            $usuario->password = bcrypt('84278870');
            $usuario->save();
        }

        if(User::where('email','=','PAULOCPEREIRA@correios.com.br')->count()){
            $usuario = User::where('email','=','PAULOCPEREIRA@correios.com.br')->first();
            $usuario->name	 = 'PAULO CESAR ALVES PEREIRA';
            $usuario->document	 = '89156250';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'PAULOCPEREIRA@correios.com.br';
            $usuario->password = bcrypt('89156250');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'PAULO CESAR ALVES PEREIRA';
            $usuario->document	 = '89156250';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'PAULOCPEREIRA@correios.com.br';
            $usuario->password = bcrypt('89156250');
            $usuario->save();
        }

        if(User::where('email','=','paulomartinsp@correios.com.br')->count()){
            $usuario = User::where('email','=','paulomartinsp@correios.com.br')->first();
            $usuario->name	 = 'PAULO MARTINS PEREIRA';
            $usuario->document	 = '81328192';
            $usuario->businessUnit	 = '434447';
            $usuario->email	 = 'paulomartinsp@correios.com.br';
            $usuario->password = bcrypt('81328192');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'PAULO MARTINS PEREIRA';
            $usuario->document	 = '81328192';
            $usuario->businessUnit	 = '434447';
            $usuario->email	 = 'paulomartinsp@correios.com.br';
            $usuario->password = bcrypt('81328192');
            $usuario->save();
        }

        if(User::where('email','=','pedote@correios.com.br')->count()){
            $usuario = User::where('email','=','pedote@correios.com.br')->first();
            $usuario->name	 = 'JOAO MARCELLO PEDOTE';
            $usuario->document	 = '89506324';
            $usuario->businessUnit	 = '434459';
            $usuario->email	 = 'pedote@correios.com.br';
            $usuario->password = bcrypt('89506324');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'JOAO MARCELLO PEDOTE';
            $usuario->document	 = '89506324';
            $usuario->businessUnit	 = '434459';
            $usuario->email	 = 'pedote@correios.com.br';
            $usuario->password = bcrypt('89506324');
            $usuario->save();
        }

        if(User::where('email','=','pedrobochilof@correios.com.br')->count()){
            $usuario = User::where('email','=','pedrobochilof@correios.com.br')->first();
            $usuario->name	 = 'PEDRO ROBERTO BOCHILOF';
            $usuario->document	 = '85639338';
            $usuario->businessUnit	 = '434062';
            $usuario->email	 = 'pedrobochilof@correios.com.br';
            $usuario->password = bcrypt('85639338');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'PEDRO ROBERTO BOCHILOF';
            $usuario->document	 = '85639338';
            $usuario->businessUnit	 = '434062';
            $usuario->email	 = 'pedrobochilof@correios.com.br';
            $usuario->password = bcrypt('85639338');
            $usuario->save();
        }

        if(User::where('email','=','phbastos@correios.com.br')->count()){
            $usuario = User::where('email','=','phbastos@correios.com.br')->first();
            $usuario->name	 = 'PAULO HENRIQUE BASTOS';
            $usuario->document	 = '89116763';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'phbastos@correios.com.br';
            $usuario->password = bcrypt('89116763');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'PAULO HENRIQUE BASTOS';
            $usuario->document	 = '89116763';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'phbastos@correios.com.br';
            $usuario->password = bcrypt('89116763');
            $usuario->save();
        }

        if(User::where('email','=','QUARESMA@correios.com.br')->count()){
            $usuario = User::where('email','=','QUARESMA@correios.com.br')->first();
            $usuario->name	 = 'FRANCISCO QUARESMA DE CARVALHO SOBRINHO';
            $usuario->document	 = '85758965';
            $usuario->businessUnit	 = '434450';
            $usuario->email	 = 'QUARESMA@correios.com.br';
            $usuario->password = bcrypt('85758965');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'FRANCISCO QUARESMA DE CARVALHO SOBRINHO';
            $usuario->document	 = '85758965';
            $usuario->businessUnit	 = '434450';
            $usuario->email	 = 'QUARESMA@correios.com.br';
            $usuario->password = bcrypt('85758965');
            $usuario->save();
        }

        if(User::where('email','=','QUINHO@correios.com.br')->count()){
            $usuario = User::where('email','=','QUINHO@correios.com.br')->first();
            $usuario->name	 = 'JOAQUIM APARECIDO DA SILVA';
            $usuario->document	 = '86913557';
            $usuario->businessUnit	 = '434457';
            $usuario->email	 = 'QUINHO@correios.com.br';
            $usuario->password = bcrypt('86913557');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'JOAQUIM APARECIDO DA SILVA';
            $usuario->document	 = '86913557';
            $usuario->businessUnit	 = '434457';
            $usuario->email	 = 'QUINHO@correios.com.br';
            $usuario->password = bcrypt('86913557');
            $usuario->save();
        }

        if(User::where('email','=','quiteriafagundes@correios.com.br')->count()){
            $usuario = User::where('email','=','quiteriafagundes@correios.com.br')->first();
            $usuario->name	 = 'QUITERIA APARECIDA FAGUNDES DE OLIVEIRA';
            $usuario->document	 = '84138289';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'quiteriafagundes@correios.com.br';
            $usuario->password = bcrypt('84138289');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'QUITERIA APARECIDA FAGUNDES DE OLIVEIRA';
            $usuario->document	 = '84138289';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'quiteriafagundes@correios.com.br';
            $usuario->password = bcrypt('84138289');
            $usuario->save();
        }

        if(User::where('email','=','rafaeldni@correios.com.br')->count()){
            $usuario = User::where('email','=','rafaeldni@correios.com.br')->first();
            $usuario->name	 = 'RAFAEL DELLA NINA IDALGO';
            $usuario->document	 = '86871137';
            $usuario->businessUnit	 = '434457';
            $usuario->email	 = 'rafaeldni@correios.com.br';
            $usuario->password = bcrypt('86871137');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'RAFAEL DELLA NINA IDALGO';
            $usuario->document	 = '86871137';
            $usuario->businessUnit	 = '434457';
            $usuario->email	 = 'rafaeldni@correios.com.br';
            $usuario->password = bcrypt('86871137');
            $usuario->save();
        }

        if(User::where('email','=','rafaeljds@correios.com.br')->count()){
            $usuario = User::where('email','=','rafaeljds@correios.com.br')->first();
            $usuario->name	 = 'RAFAEL JOSE DURAES DOS SANTOS';
            $usuario->document	 = '85653942';
            $usuario->businessUnit	 = '434456';
            $usuario->email	 = 'rafaeljds@correios.com.br';
            $usuario->password = bcrypt('85653942');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'RAFAEL JOSE DURAES DOS SANTOS';
            $usuario->document	 = '85653942';
            $usuario->businessUnit	 = '434456';
            $usuario->email	 = 'rafaeljds@correios.com.br';
            $usuario->password = bcrypt('85653942');
            $usuario->save();
        }

        if(User::where('email','=','RafaelSobral@correios.com.br')->count()){
            $usuario = User::where('email','=','RafaelSobral@correios.com.br')->first();
            $usuario->name	 = 'RAFAEL ATAIDES DE SOUZA SOBRAL';
            $usuario->document	 = '83450270';
            $usuario->businessUnit	 = '434452';
            $usuario->email	 = 'RafaelSobral@correios.com.br';
            $usuario->password = bcrypt('83450270');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'RAFAEL ATAIDES DE SOUZA SOBRAL';
            $usuario->document	 = '83450270';
            $usuario->businessUnit	 = '434452';
            $usuario->email	 = 'RafaelSobral@correios.com.br';
            $usuario->password = bcrypt('83450270');
            $usuario->save();
        }

        if(User::where('email','=','raimundogarcez@correios.com.br')->count()){
            $usuario = User::where('email','=','raimundogarcez@correios.com.br')->first();
            $usuario->name	 = 'RAIMUNDO GARCEZ DE SOUSA NETO';
            $usuario->document	 = '89514475';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'raimundogarcez@correios.com.br';
            $usuario->password = bcrypt('89514475');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'RAIMUNDO GARCEZ DE SOUSA NETO';
            $usuario->document	 = '89514475';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'raimundogarcez@correios.com.br';
            $usuario->password = bcrypt('89514475');
            $usuario->save();
        }

        if(User::where('email','=','rangelmartins@correios.com.br')->count()){
            $usuario = User::where('email','=','rangelmartins@correios.com.br')->first();
            $usuario->name	 = 'RANGEL MARTINS DA SILVA';
            $usuario->document	 = '84158816';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'rangelmartins@correios.com.br';
            $usuario->password = bcrypt('84158816');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'RANGEL MARTINS DA SILVA';
            $usuario->document	 = '84158816';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'rangelmartins@correios.com.br';
            $usuario->password = bcrypt('84158816');
            $usuario->save();
        }

        if(User::where('email','=','raulcarli@correios.com.br')->count()){
            $usuario = User::where('email','=','raulcarli@correios.com.br')->first();
            $usuario->name	 = 'RAUL CARLI';
            $usuario->document	 = '88943216';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'raulcarli@correios.com.br';
            $usuario->password = bcrypt('88943216');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'RAUL CARLI';
            $usuario->document	 = '88943216';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'raulcarli@correios.com.br';
            $usuario->password = bcrypt('88943216');
            $usuario->save();
        }

        if(User::where('email','=','Relisson@correios.com.br')->count()){
            $usuario = User::where('email','=','Relisson@correios.com.br')->first();
            $usuario->name	 = 'GERALDO RELISSON DE ARAUJO PIRES';
            $usuario->document	 = '86268627';
            $usuario->businessUnit	 = '434465';
            $usuario->email	 = 'Relisson@correios.com.br';
            $usuario->password = bcrypt('86268627');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'GERALDO RELISSON DE ARAUJO PIRES';
            $usuario->document	 = '86268627';
            $usuario->businessUnit	 = '434465';
            $usuario->email	 = 'Relisson@correios.com.br';
            $usuario->password = bcrypt('86268627');
            $usuario->save();
        }

        if(User::where('email','=','renataventapane@correios.com.br')->count()){
            $usuario = User::where('email','=','renataventapane@correios.com.br')->first();
            $usuario->name	 = 'RENATA VENTAPANE MALTA NUNES';
            $usuario->document	 = '89535383';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'renataventapane@correios.com.br';
            $usuario->password = bcrypt('89535383');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'RENATA VENTAPANE MALTA NUNES';
            $usuario->document	 = '89535383';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'renataventapane@correios.com.br';
            $usuario->password = bcrypt('89535383');
            $usuario->save();
        }

        if(User::where('email','=','renato.nascimento@correios.com.br')->count()){
            $usuario = User::where('email','=','renato.nascimento@correios.com.br')->first();
            $usuario->name	 = 'RENATO JOSE BORGES NASCIMENTO';
            $usuario->document	 = '80863124';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'renato.nascimento@correios.com.br';
            $usuario->password = bcrypt('80863124');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'RENATO JOSE BORGES NASCIMENTO';
            $usuario->document	 = '80863124';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'renato.nascimento@correios.com.br';
            $usuario->password = bcrypt('80863124');
            $usuario->save();
        }

        if(User::where('email','=','reny@correios.com.br')->count()){
            $usuario = User::where('email','=','reny@correios.com.br')->first();
            $usuario->name	 = 'RENY DAVI DE GOIS';
            $usuario->document	 = '80121012';
            $usuario->businessUnit	 = '434057';
            $usuario->email	 = 'reny@correios.com.br';
            $usuario->password = bcrypt('80121012');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'RENY DAVI DE GOIS';
            $usuario->document	 = '80121012';
            $usuario->businessUnit	 = '434057';
            $usuario->email	 = 'reny@correios.com.br';
            $usuario->password = bcrypt('80121012');
            $usuario->save();
        }

        if(User::where('email','=','RERODRIGUES@correios.com.br')->count()){
            $usuario = User::where('email','=','RERODRIGUES@correios.com.br')->first();
            $usuario->name	 = 'RENATO RODRIGUES LIMA';
            $usuario->document	 = '89268032';
            $usuario->businessUnit	 = '434859';
            $usuario->email	 = 'RERODRIGUES@correios.com.br';
            $usuario->password = bcrypt('89268032');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'RENATO RODRIGUES LIMA';
            $usuario->document	 = '89268032';
            $usuario->businessUnit	 = '434859';
            $usuario->email	 = 'RERODRIGUES@correios.com.br';
            $usuario->password = bcrypt('89268032');
            $usuario->save();
        }

        if(User::where('email','=','RICARDOXAVIER@correios.com.br')->count()){
            $usuario = User::where('email','=','RICARDOXAVIER@correios.com.br')->first();
            $usuario->name	 = 'RICARDO LOPES XAVIER';
            $usuario->document	 = '89177703';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'RICARDOXAVIER@correios.com.br';
            $usuario->password = bcrypt('89177703');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'RICARDO LOPES XAVIER';
            $usuario->document	 = '89177703';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'RICARDOXAVIER@correios.com.br';
            $usuario->password = bcrypt('89177703');
            $usuario->save();
        }

        if(User::where('email','=','Ritapilar@correios.com.br')->count()){
            $usuario = User::where('email','=','Ritapilar@correios.com.br')->first();
            $usuario->name	 = 'RITA PILAR SANCHEZ DE LA CRUZ';
            $usuario->document	 = '85608777';
            $usuario->businessUnit	 = '434461';
            $usuario->email	 = 'Ritapilar@correios.com.br';
            $usuario->password = bcrypt('85608777');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'RITA PILAR SANCHEZ DE LA CRUZ';
            $usuario->document	 = '85608777';
            $usuario->businessUnit	 = '434461';
            $usuario->email	 = 'Ritapilar@correios.com.br';
            $usuario->password = bcrypt('85608777');
            $usuario->save();
        }

        if(User::where('email','=','RLOPES@correios.com.br')->count()){
            $usuario = User::where('email','=','RLOPES@correios.com.br')->first();
            $usuario->name	 = 'ROMULO LOPES CAMURÇA';
            $usuario->document	 = '81794312';
            $usuario->businessUnit	 = '434460';
            $usuario->email	 = 'RLOPES@correios.com.br';
            $usuario->password = bcrypt('81794312');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ROMULO LOPES CAMURÇA';
            $usuario->document	 = '81794312';
            $usuario->businessUnit	 = '434460';
            $usuario->email	 = 'RLOPES@correios.com.br';
            $usuario->password = bcrypt('81794312');
            $usuario->save();
        }

        if(User::where('email','=','rmedeiros@correios.com.br')->count()){
            $usuario = User::where('email','=','rmedeiros@correios.com.br')->first();
            $usuario->name	 = 'RÉGIS FERNANDES SILVA MEDEIROS';
            $usuario->document	 = '86803468';
            $usuario->businessUnit	 = '434457';
            $usuario->email	 = 'rmedeiros@correios.com.br';
            $usuario->password = bcrypt('86803468');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'RÉGIS FERNANDES SILVA MEDEIROS';
            $usuario->document	 = '86803468';
            $usuario->businessUnit	 = '434457';
            $usuario->email	 = 'rmedeiros@correios.com.br';
            $usuario->password = bcrypt('86803468');
            $usuario->save();
        }

        if(User::where('email','=','ROBERTAA@correios.com.br')->count()){
            $usuario = User::where('email','=','ROBERTAA@correios.com.br')->first();
            $usuario->name	 = 'ROBERTA FREITAS DE ABREU ANDRADE DA SILVA';
            $usuario->document	 = '80269729';
            $usuario->businessUnit	 = '434463';
            $usuario->email	 = 'ROBERTAA@correios.com.br';
            $usuario->password = bcrypt('80269729');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ROBERTA FREITAS DE ABREU ANDRADE DA SILVA';
            $usuario->document	 = '80269729';
            $usuario->businessUnit	 = '434463';
            $usuario->email	 = 'ROBERTAA@correios.com.br';
            $usuario->password = bcrypt('80269729');
            $usuario->save();
        }

        if(User::where('email','=','ROBERTONASCIMENTO@correios.com.br')->count()){
            $usuario = User::where('email','=','ROBERTONASCIMENTO@correios.com.br')->first();
            $usuario->name	 = 'ROBERTO DO NASCIMENTO SILVA';
            $usuario->document	 = '84767952';
            $usuario->businessUnit	 = '434462';
            $usuario->email	 = 'ROBERTONASCIMENTO@correios.com.br';
            $usuario->password = bcrypt('84767952');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ROBERTO DO NASCIMENTO SILVA';
            $usuario->document	 = '84767952';
            $usuario->businessUnit	 = '434462';
            $usuario->email	 = 'ROBERTONASCIMENTO@correios.com.br';
            $usuario->password = bcrypt('84767952');
            $usuario->save();
        }

        if(User::where('email','=','robertosatoshi@correios.com.br')->count()){
            $usuario = User::where('email','=','robertosatoshi@correios.com.br')->first();
            $usuario->name	 = 'ROBERTO SATOSHI IDO';
            $usuario->document	 = '81098529';
            $usuario->businessUnit	 = '434458';
            $usuario->email	 = 'robertosatoshi@correios.com.br';
            $usuario->password = bcrypt('81098529');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ROBERTO SATOSHI IDO';
            $usuario->document	 = '81098529';
            $usuario->businessUnit	 = '434458';
            $usuario->email	 = 'robertosatoshi@correios.com.br';
            $usuario->password = bcrypt('81098529');
            $usuario->save();
        }

        if(User::where('email','=','rodrigomoreira@correios.com.br')->count()){
            $usuario = User::where('email','=','rodrigomoreira@correios.com.br')->first();
            $usuario->name	 = 'RODRIGO MOREIRA DE FIGUEIREDO';
            $usuario->document	 = '81349360';
            $usuario->businessUnit	 = '434447';
            $usuario->email	 = 'rodrigomoreira@correios.com.br';
            $usuario->password = bcrypt('81349360');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'RODRIGO MOREIRA DE FIGUEIREDO';
            $usuario->document	 = '81349360';
            $usuario->businessUnit	 = '434447';
            $usuario->email	 = 'rodrigomoreira@correios.com.br';
            $usuario->password = bcrypt('81349360');
            $usuario->save();
        }

        if(User::where('email','=','rodrigoscampos@correios.com.br')->count()){
            $usuario = User::where('email','=','rodrigoscampos@correios.com.br')->first();
            $usuario->name	 = 'RODRIGO DA SILVA CAMPOS';
            $usuario->document	 = '84169834';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'rodrigoscampos@correios.com.br';
            $usuario->password = bcrypt('84169834');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'RODRIGO DA SILVA CAMPOS';
            $usuario->document	 = '84169834';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'rodrigoscampos@correios.com.br';
            $usuario->password = bcrypt('84169834');
            $usuario->save();
        }

        if(User::where('email','=','RODRIGOSILVA@correios.com.br')->count()){
            $usuario = User::where('email','=','RODRIGOSILVA@correios.com.br')->first();
            $usuario->name	 = 'RODRIGO RODOLFO DA SILVA';
            $usuario->document	 = '89235070';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'RODRIGOSILVA@correios.com.br';
            $usuario->password = bcrypt('89235070');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'RODRIGO RODOLFO DA SILVA';
            $usuario->document	 = '89235070';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'RODRIGOSILVA@correios.com.br';
            $usuario->password = bcrypt('89235070');
            $usuario->save();
        }

        if(User::where('email','=','rogerio.amaral@correios.com.br')->count()){
            $usuario = User::where('email','=','rogerio.amaral@correios.com.br')->first();
            $usuario->name	 = 'ROGERIO BITENCOURT AMARAL';
            $usuario->document	 = '80860796';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'rogerio.amaral@correios.com.br';
            $usuario->password = bcrypt('80860796');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ROGERIO BITENCOURT AMARAL';
            $usuario->document	 = '80860796';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'rogerio.amaral@correios.com.br';
            $usuario->password = bcrypt('80860796');
            $usuario->save();
        }

        if(User::where('email','=','rogerio.darla@correios.com.br')->count()){
            $usuario = User::where('email','=','rogerio.darla@correios.com.br')->first();
            $usuario->name	 = 'ROGERIO DARLA DA SILVA';
            $usuario->document	 = '82032742';
            $usuario->businessUnit	 = '434468';
            $usuario->email	 = 'rogerio.darla@correios.com.br';
            $usuario->password = bcrypt('82032742');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ROGERIO DARLA DA SILVA';
            $usuario->document	 = '82032742';
            $usuario->businessUnit	 = '434468';
            $usuario->email	 = 'rogerio.darla@correios.com.br';
            $usuario->password = bcrypt('82032742');
            $usuario->save();
        }

        if(User::where('email','=','rogeriomn@correios.com.br')->count()){
            $usuario = User::where('email','=','rogeriomn@correios.com.br')->first();
            $usuario->name	 = 'ROGERIO MORAIS DO NASCIMENTO';
            $usuario->document	 = '81801645';
            $usuario->businessUnit	 = '434460';
            $usuario->email	 = 'rogeriomn@correios.com.br';
            $usuario->password = bcrypt('81801645');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ROGERIO MORAIS DO NASCIMENTO';
            $usuario->document	 = '81801645';
            $usuario->businessUnit	 = '434460';
            $usuario->email	 = 'rogeriomn@correios.com.br';
            $usuario->password = bcrypt('81801645');
            $usuario->save();
        }

        if(User::where('email','=','ronaldomoreira@correios.com.br')->count()){
            $usuario = User::where('email','=','ronaldomoreira@correios.com.br')->first();
            $usuario->name	 = 'RONALDO MOREIRA DE ABREU';
            $usuario->document	 = '84101571';
            $usuario->businessUnit	 = '434447';
            $usuario->email	 = 'ronaldomoreira@correios.com.br';
            $usuario->password = bcrypt('84101571');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'RONALDO MOREIRA DE ABREU';
            $usuario->document	 = '84101571';
            $usuario->businessUnit	 = '434447';
            $usuario->email	 = 'ronaldomoreira@correios.com.br';
            $usuario->password = bcrypt('84101571');
            $usuario->save();
        }

        if(User::where('email','=','rosanes@correios.com.br')->count()){
            $usuario = User::where('email','=','rosanes@correios.com.br')->first();
            $usuario->name	 = 'ROSANE SIQUEIRA BASTOS';
            $usuario->document	 = '84542934';
            $usuario->businessUnit	 = '434449';
            $usuario->email	 = 'rosanes@correios.com.br';
            $usuario->password = bcrypt('84542934');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ROSANE SIQUEIRA BASTOS';
            $usuario->document	 = '84542934';
            $usuario->businessUnit	 = '434449';
            $usuario->email	 = 'rosanes@correios.com.br';
            $usuario->password = bcrypt('84542934');
            $usuario->save();
        }

        if(User::where('email','=','rosilanebrito@correios.com.br')->count()){
            $usuario = User::where('email','=','rosilanebrito@correios.com.br')->first();
            $usuario->name	 = 'ROSILANE DA SILVA BRITO BACELAR';
            $usuario->document	 = '85269344';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'rosilanebrito@correios.com.br';
            $usuario->password = bcrypt('85269344');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ROSILANE DA SILVA BRITO BACELAR';
            $usuario->document	 = '85269344';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'rosilanebrito@correios.com.br';
            $usuario->password = bcrypt('85269344');
            $usuario->save();
        }

        if(User::where('email','=','rwerbety@correios.com.br')->count()){
            $usuario = User::where('email','=','rwerbety@correios.com.br')->first();
            $usuario->name	 = 'ROBERTO WERBETY RIBEIRO CASTRO';
            $usuario->document	 = '81793510';
            $usuario->businessUnit	 = '434460';
            $usuario->email	 = 'rwerbety@correios.com.br';
            $usuario->password = bcrypt('81793510');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ROBERTO WERBETY RIBEIRO CASTRO';
            $usuario->document	 = '81793510';
            $usuario->businessUnit	 = '434460';
            $usuario->email	 = 'rwerbety@correios.com.br';
            $usuario->password = bcrypt('81793510');
            $usuario->save();
        }

        if(User::where('email','=','SamuelB@correios.com.br')->count()){
            $usuario = User::where('email','=','SamuelB@correios.com.br')->first();
            $usuario->name	 = 'SAMUEL BERTO DOS SANTOS';
            $usuario->document	 = '85042145';
            $usuario->businessUnit	 = '434463';
            $usuario->email	 = 'SamuelB@correios.com.br';
            $usuario->password = bcrypt('85042145');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'SAMUEL BERTO DOS SANTOS';
            $usuario->document	 = '85042145';
            $usuario->businessUnit	 = '434463';
            $usuario->email	 = 'SamuelB@correios.com.br';
            $usuario->password = bcrypt('85042145');
            $usuario->save();
        }

        if(User::where('email','=','sandrorsilva@correios.com.br')->count()){
            $usuario = User::where('email','=','sandrorsilva@correios.com.br')->first();
            $usuario->name	 = 'SANDRO ROGERIO DA SILVA';
            $usuario->document	 = '89148320';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'sandrorsilva@correios.com.br';
            $usuario->password = bcrypt('89148320');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'SANDRO ROGERIO DA SILVA';
            $usuario->document	 = '89148320';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'sandrorsilva@correios.com.br';
            $usuario->password = bcrypt('89148320');
            $usuario->save();
        }

        if(User::where('email','=','sararomero@correios.com.br')->count()){
            $usuario = User::where('email','=','sararomero@correios.com.br')->first();
            $usuario->name	 = 'SARA YOSHIE ROMERO';
            $usuario->document	 = '85644935';
            $usuario->businessUnit	 = '434061';
            $usuario->email	 = 'sararomero@correios.com.br';
            $usuario->password = bcrypt('85644935');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'SARA YOSHIE ROMERO';
            $usuario->document	 = '85644935';
            $usuario->businessUnit	 = '434061';
            $usuario->email	 = 'sararomero@correios.com.br';
            $usuario->password = bcrypt('85644935');
            $usuario->save();
        }

        if(User::where('email','=','selianer@correios.com.br')->count()){
            $usuario = User::where('email','=','selianer@correios.com.br')->first();
            $usuario->name	 = 'SELIANE ROBLES';
            $usuario->document	 = '81092245';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'selianer@correios.com.br';
            $usuario->password = bcrypt('81092245');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'SELIANE ROBLES';
            $usuario->document	 = '81092245';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'selianer@correios.com.br';
            $usuario->password = bcrypt('81092245');
            $usuario->save();
        }

        if(User::where('email','=','serruya@correios.com.br')->count()){
            $usuario = User::where('email','=','serruya@correios.com.br')->first();
            $usuario->name	 = 'RAQUEL SERRUYA';
            $usuario->document	 = '80515401';
            $usuario->businessUnit	 = '434446';
            $usuario->email	 = 'serruya@correios.com.br';
            $usuario->password = bcrypt('80515401');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'RAQUEL SERRUYA';
            $usuario->document	 = '80515401';
            $usuario->businessUnit	 = '434446';
            $usuario->email	 = 'serruya@correios.com.br';
            $usuario->password = bcrypt('80515401');
            $usuario->save();
        }

        if(User::where('email','=','silvanamirtes@correios.com.br')->count()){
            $usuario = User::where('email','=','silvanamirtes@correios.com.br')->first();
            $usuario->name	 = 'SILVANA MIRTES MORAIS ALVES';
            $usuario->document	 = '81333315';
            $usuario->businessUnit	 = '434447';
            $usuario->email	 = 'silvanamirtes@correios.com.br';
            $usuario->password = bcrypt('81333315');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'SILVANA MIRTES MORAIS ALVES';
            $usuario->document	 = '81333315';
            $usuario->businessUnit	 = '434447';
            $usuario->email	 = 'silvanamirtes@correios.com.br';
            $usuario->password = bcrypt('81333315');
            $usuario->save();
        }

        if(User::where('email','=','silvanamoraes@correios.com.br')->count()){
            $usuario = User::where('email','=','silvanamoraes@correios.com.br')->first();
            $usuario->name	 = 'SILVANA MORAES DE CASTRO E SILVA';
            $usuario->document	 = '89532635';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'silvanamoraes@correios.com.br';
            $usuario->password = bcrypt('89532635');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'SILVANA MORAES DE CASTRO E SILVA';
            $usuario->document	 = '89532635';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'silvanamoraes@correios.com.br';
            $usuario->password = bcrypt('89532635');
            $usuario->save();
        }

        if(User::where('email','=','SILVERIO@correios.com.br')->count()){
            $usuario = User::where('email','=','SILVERIO@correios.com.br')->first();
            $usuario->name	 = 'SILVERIO JOSE DE ALBUQUERQUE SILVA';
            $usuario->document	 = '85044261';
            $usuario->businessUnit	 = '434456';
            $usuario->email	 = 'SILVERIO@correios.com.br';
            $usuario->password = bcrypt('85044261');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'SILVERIO JOSE DE ALBUQUERQUE SILVA';
            $usuario->document	 = '85044261';
            $usuario->businessUnit	 = '434456';
            $usuario->email	 = 'SILVERIO@correios.com.br';
            $usuario->password = bcrypt('85044261');
            $usuario->save();
        }

        if(User::where('email','=','solange.sato@correios.com.br')->count()){
            $usuario = User::where('email','=','solange.sato@correios.com.br')->first();
            $usuario->name	 = 'SOLANGE MIWAKO SATO';
            $usuario->document	 = '85673579';
            $usuario->businessUnit	 = '434456';
            $usuario->email	 = 'solange.sato@correios.com.br';
            $usuario->password = bcrypt('85673579');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'SOLANGE MIWAKO SATO';
            $usuario->document	 = '85673579';
            $usuario->businessUnit	 = '434456';
            $usuario->email	 = 'solange.sato@correios.com.br';
            $usuario->password = bcrypt('85673579');
            $usuario->save();
        }

        if(User::where('email','=','sunamita@correios.com.br')->count()){
            $usuario = User::where('email','=','sunamita@correios.com.br')->first();
            $usuario->name	 = 'SUNAMITA DA SILVA TELES';
            $usuario->document	 = '81799691';
            $usuario->businessUnit	 = '434460';
            $usuario->email	 = 'sunamita@correios.com.br';
            $usuario->password = bcrypt('81799691');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'SUNAMITA DA SILVA TELES';
            $usuario->document	 = '81799691';
            $usuario->businessUnit	 = '434460';
            $usuario->email	 = 'sunamita@correios.com.br';
            $usuario->password = bcrypt('81799691');
            $usuario->save();
        }

        if(User::where('email','=','tarcisiof@correios.com.br')->count()){
            $usuario = User::where('email','=','tarcisiof@correios.com.br')->first();
            $usuario->name	 = 'TARCISIOFONSECA SANTOS';
            $usuario->document	 = '80865755';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'tarcisiof@correios.com.br';
            $usuario->password = bcrypt('80865755');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'TARCISIOFONSECA SANTOS';
            $usuario->document	 = '80865755';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'tarcisiof@correios.com.br';
            $usuario->password = bcrypt('80865755');
            $usuario->save();
        }

        if(User::where('email','=','teciogomes@correios.com.br')->count()){
            $usuario = User::where('email','=','teciogomes@correios.com.br')->first();
            $usuario->name	 = 'TECIO LIMA GOMES';
            $usuario->document	 = '80144276';
            $usuario->businessUnit	 = '434057';
            $usuario->email	 = 'teciogomes@correios.com.br';
            $usuario->password = bcrypt('80144276');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'TECIO LIMA GOMES';
            $usuario->document	 = '80144276';
            $usuario->businessUnit	 = '434057';
            $usuario->email	 = 'teciogomes@correios.com.br';
            $usuario->password = bcrypt('80144276');
            $usuario->save();
        }

        if(User::where('email','=','thais.fernanda@correios.com.br')->count()){
            $usuario = User::where('email','=','thais.fernanda@correios.com.br')->first();
            $usuario->name	 = 'THAIS FERNANDA LIMA DE CASTRO';
            $usuario->document	 = '83454128';
            $usuario->businessUnit	 = '434452';
            $usuario->email	 = 'thais.fernanda@correios.com.br';
            $usuario->password = bcrypt('83454128');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'THAIS FERNANDA LIMA DE CASTRO';
            $usuario->document	 = '83454128';
            $usuario->businessUnit	 = '434452';
            $usuario->email	 = 'thais.fernanda@correios.com.br';
            $usuario->password = bcrypt('83454128');
            $usuario->save();
        }

        if(User::where('email','=','THIAGOVAZZOLER@correios.com.br')->count()){
            $usuario = User::where('email','=','THIAGOVAZZOLER@correios.com.br')->first();
            $usuario->name	 = 'THIAGO VAZZOLER';
            $usuario->document	 = '89181417';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'THIAGOVAZZOLER@correios.com.br';
            $usuario->password = bcrypt('89181417');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'THIAGO VAZZOLER';
            $usuario->document	 = '89181417';
            $usuario->businessUnit	 = '434469';
            $usuario->email	 = 'THIAGOVAZZOLER@correios.com.br';
            $usuario->password = bcrypt('89181417');
            $usuario->save();
        }

        if(User::where('email','=','Valente@correios.com.br')->count()){
            $usuario = User::where('email','=','Valente@correios.com.br')->first();
            $usuario->name	 = 'ANTONIO MARCOS DOS SANTOS VALENTE';
            $usuario->document	 = '84531606';
            $usuario->businessUnit	 = '434449';
            $usuario->email	 = 'Valente@correios.com.br';
            $usuario->password = bcrypt('84531606');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ANTONIO MARCOS DOS SANTOS VALENTE';
            $usuario->document	 = '84531606';
            $usuario->businessUnit	 = '434449';
            $usuario->email	 = 'Valente@correios.com.br';
            $usuario->password = bcrypt('84531606');
            $usuario->save();
        }

        if(User::where('email','=','vandersonribeiro@correios.com.br')->count()){
            $usuario = User::where('email','=','vandersonribeiro@correios.com.br')->first();
            $usuario->name	 = 'VANDERSON RIBEIRO DE SOUZA DA CONCEIÇÃO';
            $usuario->document	 = '89550439';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'vandersonribeiro@correios.com.br';
            $usuario->password = bcrypt('89550439');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'VANDERSON RIBEIRO DE SOUZA DA CONCEIÇÃO';
            $usuario->document	 = '89550439';
            $usuario->businessUnit	 = '434470';
            $usuario->email	 = 'vandersonribeiro@correios.com.br';
            $usuario->password = bcrypt('89550439');
            $usuario->save();
        }

        if(User::where('email','=','VIEIRASAN@correios.com.br')->count()){
            $usuario = User::where('email','=','VIEIRASAN@correios.com.br')->first();
            $usuario->name	 = 'MARCOS VIEIRA DOS SANTOS';
            $usuario->document	 = '89056582';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'VIEIRASAN@correios.com.br';
            $usuario->password = bcrypt('89056582');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'MARCOS VIEIRA DOS SANTOS';
            $usuario->document	 = '89056582';
            $usuario->businessUnit	 = '434063';
            $usuario->email	 = 'VIEIRASAN@correios.com.br';
            $usuario->password = bcrypt('89056582');
            $usuario->save();
        }

        if(User::where('email','=','VITALFERNANDES@correios.com.br')->count()){
            $usuario = User::where('email','=','VITALFERNANDES@correios.com.br')->first();
            $usuario->name	 = 'VITAL FERNANDES NETO';
            $usuario->document	 = '88931064';
            $usuario->businessUnit	 = '434465';
            $usuario->email	 = 'VITALFERNANDES@correios.com.br';
            $usuario->password = bcrypt('88931064');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'VITAL FERNANDES NETO';
            $usuario->document	 = '88931064';
            $usuario->businessUnit	 = '434465';
            $usuario->email	 = 'VITALFERNANDES@correios.com.br';
            $usuario->password = bcrypt('88931064');
            $usuario->save();
        }

        if(User::where('email','=','Vivaldo.Carvalho@correios.com.br')->count()){
            $usuario = User::where('email','=','Vivaldo.Carvalho@correios.com.br')->first();
            $usuario->name	 = 'VIVALDO CARVALHO SILVA';
            $usuario->document	 = '80856144';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'Vivaldo.Carvalho@correios.com.br';
            $usuario->password = bcrypt('80856144');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'VIVALDO CARVALHO SILVA';
            $usuario->document	 = '80856144';
            $usuario->businessUnit	 = '434453';
            $usuario->email	 = 'Vivaldo.Carvalho@correios.com.br';
            $usuario->password = bcrypt('80856144');
            $usuario->save();
        }

        if(User::where('email','=','elias.silva@correios.com.br')->count()){
            $usuario = User::where('email','=','elias.silva@correios.com.br')->first();
            $usuario->name	 = 'ELIAS SILVA';
            $usuario->document	 = '83298240';
            $usuario->businessUnit	 = '434448';
            $usuario->email	 = 'elias.silva@correios.com.br';
            $usuario->password = bcrypt('83298240');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ELIAS SILVA';
            $usuario->document	 = '83298240';
            $usuario->businessUnit	 = '434448';
            $usuario->email	 = 'elias.silva@correios.com.br';
            $usuario->password = bcrypt('83298240');
            $usuario->save();
        }

        if(User::where('email','=','VIVIENE.ANDRADE@correios.com.br')->count()){
            $usuario = User::where('email','=','VIVIENE.ANDRADE@correios.com.br')->first();
            $usuario->name	 = 'VIVIENE ANDRADE VAZ';
            $usuario->document	 = '87775948';
            $usuario->businessUnit	 = '434448';
            $usuario->email	 = 'VIVIENE.ANDRADE@correios.com.br';
            $usuario->password = bcrypt('87775948');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'VIVIENE ANDRADE VAZ';
            $usuario->document	 = '87775948';
            $usuario->businessUnit	 = '434448';
            $usuario->email	 = 'VIVIENE.ANDRADE@correios.com.br';
            $usuario->password = bcrypt('87775948');
            $usuario->save();
        }

        if(User::where('email','=','Wellington.silva@correios.com.br')->count()){
            $usuario = User::where('email','=','Wellington.silva@correios.com.br')->first();
            $usuario->name	 = 'WELINGTON DA SILVA SANTOS';
            $usuario->document	 = '83299807';
            $usuario->businessUnit	 = '434448';
            $usuario->email	 = 'Wellington.silva@correios.com.br';
            $usuario->password = bcrypt('83299807');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'WELINGTON DA SILVA SANTOS';
            $usuario->document	 = '83299807';
            $usuario->businessUnit	 = '434448';
            $usuario->email	 = 'Wellington.silva@correios.com.br';
            $usuario->password = bcrypt('83299807');
            $usuario->save();
        }

        if(User::where('email','=','wellingtondecastro@correios.com.br')->count()){
            $usuario = User::where('email','=','wellingtondecastro@correios.com.br')->first();
            $usuario->name	 = 'WELLINGTON GONCALVES DE CASTRO';
            $usuario->document	 = '84163038';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'wellingtondecastro@correios.com.br';
            $usuario->password = bcrypt('84163038');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'WELLINGTON GONCALVES DE CASTRO';
            $usuario->document	 = '84163038';
            $usuario->businessUnit	 = '434455';
            $usuario->email	 = 'wellingtondecastro@correios.com.br';
            $usuario->password = bcrypt('84163038');
            $usuario->save();
        }

        if(User::where('email','=','WSOUZA@correios.com.br')->count()){
            $usuario = User::where('email','=','WSOUZA@correios.com.br')->first();
            $usuario->name	 = 'WELLINGTON ANTONIO DE SOUZA';
            $usuario->document	 = '85046957';
            $usuario->businessUnit	 = '434463';
            $usuario->email	 = 'WSOUZA@correios.com.br';
            $usuario->password = bcrypt('85046957');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'WELLINGTON ANTONIO DE SOUZA';
            $usuario->document	 = '85046957';
            $usuario->businessUnit	 = '434463';
            $usuario->email	 = 'WSOUZA@correios.com.br';
            $usuario->password = bcrypt('85046957');
            $usuario->save();
        }

        if(User::where('email','=','rogerio.moraes@correios.com.br')->count()){
            $usuario = User::where('email','=','rogerio.moraes@correios.com.br')->first();
            $usuario->name	 = 'ROGÉRIO RODRIGUES DE MORAES';
            $usuario->document	 = '85611050';
            $usuario->businessUnit	 = '434456';
            $usuario->email	 = 'rogerio.moraes@correios.com.br';
            $usuario->password = bcrypt('85611050');
            $usuario->save();
        }else{
            $usuario = new User();
            $usuario->name	 = 'ROGÉRIO RODRIGUES DE MORAES';
            $usuario->document	 = '85611050';
            $usuario->businessUnit	 = '434456';
            $usuario->email	 = 'rogerio.moraes@correios.com.br';
            $usuario->password = bcrypt('85611050');
            $usuario->save();
        }

        echo "\n Usuario Master criado com sucesso!"
        ,"\n email = admin@compliance.com"
        ,"\n password = 12345678";

        echo "\n Demais Usuários criados com sucesso!"
        ,"\n email = >seuemail>@correios.com.br"
        ,"\n password = MATRICULA-> 99999999";

    }
}
