{extends file="main.tpl"}

{block name=content}

<div class="pure-menu pure-menu-horizontal bottom-margin">
	<a href="{$conf->action_url}logout"  class="pure-menu-heading pure-menu-link">wyloguj</a>
	<span style="float:right;">użytkownik: {$user->login}, rola: {$user->role}</span>
</div>

<form action="{$conf->action_url}calcCompute" method="post" class="pure-form pure-form-aligned bottom-margin">
	<legend>Kalkulator</legend>
	<fieldset>
        <div class="pure-control-group">
			<label for="id_x">Liczba 1: </label>
			<input id="id_x" type="text" name="x" value="{$form->x}" />
		</div>
        <div class="pure-control-group">
			<label for="id_op">Operacja: </label>
			<select name="op">
				{if isset($res->op_name)}
				<option value="{$form->op}">ponownie: {$res->op_name}</option>
				<option value="" disabled="true">---</option>
				{/if}
				<option value="plus">+</option>
				<option value="minus">-</option>
				<option value="times">*</option>
				{if $user->role == "admin"}
				<option value="div">/</option>
				{/if}
			</select>
		</div>
        <div class="pure-control-group">
			<label for="id_y">Liczba 2: </label>
			<input id="id_y" type="text" name="y" value="{$form->y}" />
		</div>
		<div class="pure-controls">
			<input type="submit" value="Oblicz" class="pure-button pure-button-primary"/>
		</div>
	</fieldset>
</form>	

{include file='messages.tpl'}

{if isset($res->result)}
<div class="messages info">
	Wynik: {$res->result}
</div>
{/if}

{/block}