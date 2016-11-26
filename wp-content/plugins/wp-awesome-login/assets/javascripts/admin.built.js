!function(a){"use strict";function b(a,b){"function"==typeof a&&a.apply(null,b||[])}function c(a,c,d){b(a.init,d),b(a[c],d)}a.dispatcher=c}(window),function(a,b){"use strict";var c='<div class="[type] render-message"><p><strong>[message]</strong></p></div>',d='<span class="spinner is-active" style="display: block;"></span>',e=function(a){this.$el=a,this.submit=this.$el.find('[data-element="submit"]'),this.init()};e.prototype.init=function(){this.addEventListener()},e.prototype.addEventListener=function(){this.$el.on("submit",this._onSubmit.bind(this))},e.prototype._onSubmit=function(a){a.preventDefault(),this.send()},e.prototype.beforeSend=function(){this.submit.attr("disabled","disabled"),this.setShowSpinner()},e.prototype.send=function(){var a=this.$el.attr("action"),b=this.$el.serialize(),c=jQuery.ajax({url:a,data:b,dataType:"json",type:"POST"});this.beforeSend(),c.done(this._done.bind(this)),c.fail(this._fail.bind(this))},e.prototype._done=function(a){this.submit.removeAttr("disabled"),this.setHideSpinner(),this.setShowMessage("updated",a.message)},e.prototype._fail=function(a,b){var c=a.responseJSON||{};this.submit.removeAttr("disabled"),this.setHideSpinner(),this.setShowMessage("error",c.message)},e.prototype.setShowMessage=function(a,b){this.$el.prev(".render-message").remove(),this.$el.before(this.renderMessage(a,b))},e.prototype.renderMessage=function(a,b){return c.replace(/\[type\]/,a).replace(/\[message\]/,b)},e.prototype.setShowSpinner=function(a){this.submit.after(d)},e.prototype.setHideSpinner=function(){this.submit.siblings(".spinner").remove()},b.ComponentForm=e}(jQuery,window),function(a,b){"use strict";var c=function(a){this.container=a,this.src=this.container.data("attr-image-src"),this.empty=this.container.data("attr-image-empty"),this.position=this.container.data("attr-image-position"),this.$el=jQuery("<img>"),this.on=jQuery.proxy(this.$el,"on"),this.fire=jQuery.proxy(this.$el,"trigger"),this.inserted=!1,this.create()};c.prototype.create=function(){(this.src||this.empty)&&(this.$el.addClass("image-create-component").attr("src",this.getSrc()),this.inserted||this._insert())},c.prototype.getSrc=function(){return this.src||this.empty},c.prototype.setEmptyImage=function(){return this.empty?void this.$el.attr("src",this.empty):void this.$el.hide()},c.prototype._insert=function(){this.inserted=!0,this.container[this.position||"before"](this.$el),this.container.addClass("created-image")},c.prototype.reload=function(a){this.src=a,this.create(),this.$el.show()};var d=function(a){this.container=a,this.value=this.container.data("attr-hidden-value"),this.name=this.container.data("attr-hidden-name"),this.type=this.container.data("attr-type")||"id",this.$el=null,this.create()};d.prototype.create=function(){this.$el=jQuery("<input>",{type:"hidden","class":"hidden-create-component",value:this.value,name:this.name}),this.container.before(this.$el)},d.prototype.val=function(a){this.value=a,this.$el.val(a)},d.prototype.isEmpty=function(){return!this.value};var e=function(a){this.container=a,this.changeButtonText=this.container.data("attr-button-text"),this.removeButtonText=this.container.data("attr-remove-text"),this.image=null,this.hidden=null,this.buttonRemove=null,this.init()};e.prototype.init=function(){this._createElements(),this._createEmptyButton(),this.addEventListener()},e.prototype._defineButtonText=function(){(window._wpMediaViewsL10n||{}).insertIntoPost=this.changeButtonText||"inserir"},e.prototype._createElements=function(){this.image=new c(this.container),this.hidden=new d(this.container)},e.prototype._createEmptyButton=function(){return this.hidden.isEmpty()?void 0:(this.container.hide(),this.isExistButtonRemove()?void this.getButtonRemove().show():void this.getButtonRemove().on("click",this._onClickButtonDelete.bind(this)).insertAfter(this.container))},e.prototype.isExistButtonRemove=function(){return this.container.next(".button-remove").length},e.prototype.getButtonRemove=function(){var a=this.removeButtonText||"Remover imagem destacada",b='<a href="javascript:void(0);" class="button-remove"></a>';return this.buttonRemove||(this.buttonRemove=jQuery(b).text(a)),this.buttonRemove},e.prototype.addEventListener=function(){this.container.on("click",this._onClickButtonUpload.bind(this)),this.image.on("click",this._onClickButtonUpload.bind(this))},e.prototype.reset=function(){this.hidden.val(""),this.image.setEmptyImage(),this.buttonRemove.hide(),this.container.show()},e.prototype._onClickButtonUpload=function(a){this._defineButtonText(),wp.media.editor.send.attachment=this._onAfterAttachmentAction(),wp.media.editor.open()},e.prototype._onClickButtonDelete=function(){this.reset()},e.prototype._onAfterAttachmentAction=function(){var a=this,b=this.hidden.type||"url";return function(c,d){a.image.reload(d.url),a.hidden.val(d[b]),a._createEmptyButton()}},b.ComponentUpload=e}(jQuery,window),jQuery(function(a){"use strict";var b={init:function(){},"appearance_page_wpal-settings-theme":function(){new ComponentForm(a('[data-component="form"]')),new ComponentUpload(a('[data-component="upload"]'));b.setColorPicker()},setColorPicker:function(){a('[data-component="color-picker"]').each(function(b,c){c=a(c),!c.val()&&c.val(c.data("default-color")),c.wpColorPicker()})}};dispatcher(b,window.pagenow,[a("body")])});