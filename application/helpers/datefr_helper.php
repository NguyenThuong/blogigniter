<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
if ( ! function_exists('datefr'))
{
    function datefr($jour, $mois, $annee)
    {
        $mois_n = $mois;
        switch ($mois) {
            case '01':
                $mois = 'Janvier';
                break;
            case '02':
                $mois = 'Février';
                break;
            case '03':
                $mois = 'Mars';
                break;
            case '04':
                $mois = 'Avril';
                break;
            case '05':
                $mois = 'Mai';
                break;
            case '06':
                $mois = 'Juin';
                break;
            case '7':
                $mois = 'Juillet';
                break;
            case '8':
                $mois = 'Aout';
                break;
            case '9':
                $mois = 'Septembre';
                break;
            case '10':
                $mois = 'Octobre';
                break;
            case '11':
                $mois = 'Novembre';
                break;
            case '12':
                $mois = 'Décembre';
                break;
            
            default:
                break;
        }

        return '<time datetime="' . $annee . '-' . $mois_n . '-' . $jour . '">' .$jour . ' ' . $mois . ' ' . $annee.'</time>';
    }
}
