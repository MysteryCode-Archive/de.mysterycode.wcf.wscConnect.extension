{include file='header' pageTitle='wcf.acp.menu.link.user.customnotifications.'|concat:$action}

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">{lang}wcf.acp.menu.link.user.customnotifications.{$action}{/lang}</h1>
	</div>

	<nav class="contentHeaderNavigation">
		<ul>
			<li><a href="{link controller='CustomNotificationList'}{/link}" class="button"><span class="icon icon16 fa-list"></span> <span>{lang}wcf.acp.menu.link.user.customnotifications.list{/lang}</span></a></li>

			{event name='contentHeaderNavigation'}
		</ul>
	</nav>
</header>

{include file='formError'}

{if $success|isset}
	<p class="success">{lang}wcf.global.success.{$action}{/lang}</p>
{/if}

<form method="post" action="{if $action == 'add'}{link controller='CustomNotificationSend'}{/link}{else}{link controller='CustomNotificationEdit' id=$notification->getObjectID()}{/link}{/if}">
	<section class="section">
		<dl{if $errorField == 'subject'} class="formError"{/if}>
			<dt><label for="subject">{lang}wcf.global.title{/lang}</label></dt>
			<dd>
				{* TODO tornado *}
				{*<input id="subject" name="subject" type="text" value="{$i18nPlainValues[subject]}">*}
				<input id="subject" name="subject" type="text" value="{$subject}" class="long" required>
				{if $errorField == 'subject'}
					<small class="innerError">
						{if $errorType == 'empty'}
							{lang}wcf.global.form.error.empty{/lang}
						{elseif $errorType == 'multilingual'}
							{lang}wcf.global.form.error.multilingual{/lang}
						{/if}
					</small>
				{/if}
			</dd>
		</dl>
		{* TODO tornado *}
		{*{include file='multipleLanguageInputJavascript' elementIdentifier='subject' forceSelection=false}*}

		<dl{if $errorField == 'url'} class="formError"{/if}>
			<dt><label for="url">{lang}wcf.acp.wsc_connect.notification.custom.url{/lang}</label></dt>
			<dd>
				{*<input id="url" name="url" type="text" value="{$$i18nPlainValues[url]}">*}
				<input id="url" name="url" type="text" value="{$url}" class="long" required>
				{*{include file='multipleLanguageInputJavascript' elementIdentifier='url' forceSelection=false}*}
				<small>{lang}wcf.acp.wsc_connect.notification.custom.url.description{/lang}</small>
				{if $errorField == 'url'}
					<small class="innerError">
						{if $errorType == 'empty'}
							{lang}wcf.global.form.error.empty{/lang}
						{else}
							{lang}wcf.acp.wsc_connect.notification.custom.form.error.url.{$errorType}{/lang}
						{/if}
					</small>
				{/if}
			</dd>
		</dl>

		<dl{if $errorField == 'isNotification'} class="formError"{/if}>
			<dt></dt>
			<dd>
				<label>
					<input type="checkbox" id="isNotification" name="isNotification" value="1"{if $isNotification} checked{/if} />
					{lang}wcf.acp.wsc_connect.notification.custom.isNotification{/lang}
				</label>
			</dd>
		</dl>

		<dl{if $errorField == 'message'} class="formError"{/if}>
			<dt><label for="message">{lang}wcf.acp.wsc_connect.notification.custom.message{/lang}</label></dt>
			<dd>
				{*<textarea id="message" name="message">{$$i18nPlainValues[url]}</textarea>*}
				<textarea id="message" name="message" class="long" required>{$url}</textarea>
				{include file='wysiwyg' wysiwygSelector='message'}
				{* TODO tornado *}
				{*{include file='multipleLanguageInputJavascript' elementIdentifier='message' forceSelection=false}*}
				<small>{lang}wcf.acp.wsc_connect.notification.custom.message.description{/lang}</small>
				{if $errorField == 'message'}
					<small class="innerError">
						{if $errorType == 'empty'}
							{lang}wcf.global.form.error.empty{/lang}
						{else}
							{lang}wcf.acp.wsc_connect.notification.custom.form.error.message.{$errorType}{/lang}
						{/if}
					</small>
				{/if}
			</dd>
		</dl>

		<dl{if $errorField == 'recipients'} class="formError"{/if}>
			<dt><label for="recipients">{lang}wcf.acp.wsc_connect.notification.custom.recipients{/lang}</label></dt>
			<dd>
				<input id="recipients" name="recipients" type="text" value="{$url}" class="long" required>
				{if $errorField == 'recipients'}
					<small class="innerError">
						{if $errorType == 'empty'}
							{lang}wcf.global.form.error.empty{/lang}
						{else}
							{lang}wcf.acp.wsc_connect.notification.custom.form.error.recipients.{$errorType}{/lang}
						{/if}
					</small>
				{/if}
			</dd>
		</dl>

		{event name='dataFields'}
	</section>

	{event name='sections'}

	<div class="formSubmit">
		<input type="submit" value="{lang}wcf.global.button.submit{/lang}" accesskey="s">
		{@SECURITY_TOKEN_INPUT_TAG}
	</div>
</form>

<script data-relocate="true">
	$(function() {
		new WCF.Search.User('#recipients', null, false, [], true);
	});
</script>

{include file='footer'}
