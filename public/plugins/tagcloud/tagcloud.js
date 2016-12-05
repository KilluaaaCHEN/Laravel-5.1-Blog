/**
 * Created by killua on 16/11/28.
 */
$( document ).ready( function() {
    var settings = {
        entries: tag_data,
        width: 260,
        height: 260,
        radius: '65%',
        radiusMin: 75,
        bgDraw: true,
        bgColor: '#fff',
        opacityOver: 1.00,
        opacityOut: 0.2,
        opacitySpeed: 6,
        fov: 800,
        speed: 0.5,
        fontFamily: 'Oswald, Arial, sans-serif',
        fontSize: '14',
        fontColor: 'black',
        fontWeight: 'normal',//bold
        fontStyle: 'normal',//italic
        fontStretch: 'normal',//wider, narrower, ultra-condensed, extra-condensed, condensed, semi-condensed, semi-expanded, expanded, extra-expanded, ultra-expanded
        fontToUpperCase: false

    };
    //var svg3DTagCloud = new SVG3DTagCloud( document.getElementById( 'holder'  ), settings );
    $( '#tag-cloud' ).svg3DTagCloud( settings );

} );
