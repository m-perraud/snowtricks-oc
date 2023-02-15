<?php 

namespace App\Service;



class VideoProcessingService
{
    // D'abord on aura vérifié la validité de l'URL : seulement du youtube, vimeo et daylimotion. 
    // Attention, plusieurs formats d'URL possibles. Ensuite le form envoie l'URL en DB. 

    // Ici on va extraire la variable pour chaque type de vidéo et chaque URL 

    public function cleanURL(string $videoURL): string
    {
            // Youtube
            $videoURL = str_replace('https://youtu.be/', 'https://www.youtube.com/embed/', $videoURL);
            $videoURL = str_replace('https://www.youtube.com/watch?v=', 'https://www.youtube.com/embed/', $videoURL);

            // Daylimotion
            $videoURL = str_replace('https://www.dailymotion.com/video/', 'https://www.dailymotion.com/embed/video/', $videoURL);
            $videoURL = str_replace('https://dai.ly/', 'https://www.dailymotion.com/embed/video/', $videoURL);

            // Vimeo
            $videoURL = str_replace('https://vimeo.com/', 'https://player.vimeo.com/video/', $videoURL);

            return $videoURL;
    }

    // Pour la partie iframe, passer le html sur twig directement  
}
