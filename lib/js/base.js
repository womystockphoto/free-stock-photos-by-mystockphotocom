jQuery(document).ready( function( $ ) {
    $('.mystp_close').click(function(){
          mystp_close();
       });
       $('#mystp_cancel').click( function(){
           $('#m_tip').hide();
           $('#m_model').show();
        });
       $('#mystp_add').click(function()
       {
        var win = window.dialogArguments || opener || parent || top;
        var divimg = null;
        var _down = parseInt( $('input[type="radio"]:checked').val() );
        var _imgstr = '[caption ';

         divimg = $('div .add-image-img');
         divprev = $('#m_preview');
        var align = $(divprev).attr('data-align');

       if( 'center' == align){
           _imgstr += ' align="aligncenter" ';
        }
       else if( 'left' == align){
          _imgstr += ' align="alignleft" ';
       }
      else {
           _imgstr += ' align="alignright" ';
       }
       _imgstr =  _imgstr + ' width="' + $(divimg).width() + '"] ';
       _imgstr += '<a href="'+$(divprev).attr('date-url')+  '" target="_blank" ><img ';
       _imgstr += ' width="' + $(divimg).width()+'" ';
       _imgstr += ' height="' + $('.add-image-img img').height()+'" ';
       _imgstr += '  alt="' + $(divprev).attr('data-cate') + '" title="' + $(divprev ).attr('data-title') +'" ';
       _imgstr = _imgstr + '  style="border:'+$(divimg).css('border')+';" ';
        if( _down )
         {
           $('#m_model').hide();
           $('#m_tip').show();

          var _data = {action:'mystp_proxy', fpage:'download',post_id:0, desc:'',url:''};

           _data.post_id = $(divprev ).attr('data-pid');
           _data.desc = $(divprev ).attr('data-title');
           _data.url = $('.add-image-img img').attr('src');

        if( _data.url )
        {
          $.post(ajaxurl, _data, function(response ) {
         if( response  &&  response.code)
         {
           _imgstr += ' src="'+response.data + '" ';
         }
         else
         {
            _imgstr += ' src="' + _data.url + '" ';
         }
          _imgstr += '/>' + _data.desc + '[/caption]';
          win.send_to_editor( _imgstr );
          parent.jQuery.fn.colorbox.close();
          },"json");
         }
      }
       else
        {
          _imgstr += ' src="' + $('.add-image-img img').attr('src')+ '" ';
          _imgstr += '/></a>' + $(divprev ).attr('data-title') + '[/caption]';
          win.send_to_editor( _imgstr );
          parent.jQuery.fn.colorbox.close();
        }
        });
 });

function  mystp_close(){
        parent.jQuery.fn.colorbox.close();
}

