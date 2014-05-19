jQuery(document).ready( function( $ ) {
  
   $(".colorpicker").spectrum({
    flat: false,
    showInput: true,
    showPalette: true,
    showInitial: true,
    showSelectionPalette: true,
    palette: [
            ["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)", "rgb(153, 153, 153)","rgb(183, 183, 183)",
            "rgb(204, 204, 204)", "rgb(217, 217, 217)", "rgb(239, 239, 239)", "rgb(243, 243, 243)", "rgb(255, 255, 255)"],
            ["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
            "rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"],
            ["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)",
            "rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)",
            "rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)",
            "rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)",
            "rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)",
            "rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
            "rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
            "rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
            "rgb(133, 32, 12)", "rgb(153, 0, 0)", "rgb(180, 95, 6)", "rgb(191, 144, 0)", "rgb(56, 118, 29)",
            "rgb(19, 79, 92)", "rgb(17, 85, 204)", "rgb(11, 83, 148)", "rgb(53, 28, 117)", "rgb(116, 27, 71)",
            "rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)",
            "rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
        ]
    });

    $(".spectrumpicker").spectrum({
    flat: true,
    showPalette: true,
    showPaletteOnly:  true,
    className: "flat-color",
    palette: [
            ["gray", "red", "orange", "yellow", "green","aqua"],
            ["blue", "purple", "pink", "brown", "white","black"]
        ]
    });
    
    //picture resize start
    $('#rz_start').click(function(){
       $('#m_list').hide();
       $('#m_tip').show();
      
    var _data = {action:'mystp_proxy', fpage:'resize',id:0, aspect_ratio: 'original',Width:0};

       _data.id = parseInt($(this).attr('data-id'));
       _data.Width = $('div .add-image-img').width();
       _data.aspect_ratio =$('#size-max').attr('data-aspect');
       
       if( _data.id ){
          $.post(ajaxurl, _data, function(response ) {
         if( response  &&  response.code)
         {
           $('#frm_img_url').val( response.data.image_url);
           $('#frm_img_width').val( response.data.width);
           $('#frm_img_hegiht').val(response.data.height);
           $('#form1').submit();
         }
         else
         {
           alert( response.msg );
           $('#m_tip').hide();
           $('#m_list').show();
         }
       },"json");    
      }  
      else
       {
         alert('Request error!');
       }
  });
     $('#rz_cancle').click( function(){
      $('#m_tip').hide();
      $('#m_list').show();
     });  
   
       var ep = 108;

       if(typeof($('#slider-range').attr('data-W')) != "undefined" )
             ep =  parseInt($('#slider-range').attr('data-W'));
      var ep2 = Math.min( ep/2 ,300);
      /* ui-slide*/
       $('#slider-range').slider({
            min: 100,
            max: ep,
            step: 8,
            value:ep2,
            change: function(event, ui) {
                $('#size-max').html( ui.value + ' px');
                $('div .add-image-img').css( 'width',ui.value+'px' );
            }
        }); 


    $('#align-center').click(function(){
           if(!$(this).hasClass("current"))
          {
            $(this).parent("li").siblings().children("a").removeClass("current");
            $(this).addClass("current");
            $('div .add-image-img').css('margin','0 auto');
            $('div .add-image-img').css('float','');
            $('#frm_img_align').val('center');
          }
       });
     $('#align-left').click(function(){
            if(!$(this).hasClass("current"))
          {
            $(this).parent("li").siblings().children("a").removeClass("current");
            $(this).addClass("current");
            $('div .add-image-img').css('margin','0 15px 15px 0');
            $('div .add-image-img').css('float','left');
            $('#frm_img_align').val('left');
          }
       });
     $('#align-right').click(function(){
            if(!$(this).hasClass("current"))
          {
            $(this).parent("li").siblings().children("a").removeClass("current");
            $(this).addClass("current");
            $('div .add-image-img').css('margin','0 0 15px 15px');
            $('div .add-image-img').css('float','right');
            $('#frm_img_align').val('right');
          }
       });
      
     $('#btn_border').change(function(){
        var _border = parseInt($(this).val());
        if( !_border ){
         $(this).val(1);
         _border = 1;
        }   
       
       $('#frm_img_border').val( _border );
       _border +='px solid ';
       if($('.colorpicker').val() ) {
          _border += $('.colorpicker').val(); 
        $('#frm_img_border_color').val($('.colorpicker').val() );
      }
      else {
         _border += '#ddd';
          $('#frm_img_border_color').val('#ddd');
         }
       $('div .add-image-img').css('border' , _border);
     });
        
     $('.colorpicker').change(function(){
       $( "#btn_border" ).trigger( "change" )
     });

     $('.aspect').click(function(){
       if(!$(this).hasClass("current"))
      {
        var d = {W:0,H:0,img:''};   
        d.W = parseInt( $(this).attr('data-width'));
        d.H = parseInt( $(this).attr('data-height'));
        d.img = $(this).attr('data-img');
        if( d.img ){
          $('.add-image-img img').attr('src', d.img );
          $(this).parent("li").siblings().children("a").removeClass("current");
          $(this).addClass("current");
          $('#frm_img_width').val(d.W);
          $('#frm_img_height').val(d.H);
          $('#frm_img_url').val( d.img );
          $('#size-max').html(d.W + 'px');
          $('#size-max').attr('data-aspect',$(this).attr('id'));
        }
      }   
     });
    
  });
