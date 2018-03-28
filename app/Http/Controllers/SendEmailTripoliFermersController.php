<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Symfony\Component\DomCrawler\Crawler as Crawler;
use Illuminate\Support\Facades\Mail;

class SendEmailTripoliFermersController extends Controller
{
    public function execute()
    {
        header('Content-type: text/html; charset=utf-8');
        $csv = $this->parseCsv();
        $this->sendMails($csv);
    }

    protected function parseCsv()
    {   
        $filename ='../storage/tripoli/tripoliFermers1.csv';
        $attachFilename ='../storage/tripoli/КП_Улица_Пром.pdf';
        $lines = file($filename, FILE_IGNORE_NEW_LINES);
        $myMails = [
            'znakd@ukr.net',
            'newsuperznak@gmail.com',
            'znakverona@gmail.com',
            'newsuperagro@gmail.com'

            ];
       
        // foreach ($myMails as $myMail) {
        //     $data = array(
        //     'name' => "NewSuperLed",
        //     );
        //     Mail::send('emails.tripoliFermers', $data, function ($message)  use ($myMail, $attachFilename) {
        //     $message->from('info@newsuperled.com.ua', 'Светодиодное освещение');
        //     $message->to($myMail)->subject('NewSuperLed. Предложение.');
        //     $message->attach($attachFilename);
        //     });
        //     pre($myMail . '  отослано!');
        // }
//pechenegitov@gmail.com
        foreach ($lines as $line) {
            $line = trim($line);
            $data = array(
            'name' => "NewSuperLed",
            );
            Mail::send('emails.tripoliFermers', $data, function ($message)  use ($line, $attachFilename) {
            $message->from('info@newsuperled.com.ua', 'Светодиодное освещение');
            $message->to($line)->subject('NewSuperLed. Предложение.');
            $message->attach($attachFilename);
            });
            pre($line . '  отослано!');
            sleep(3);
        }
    }//end parseCsv()

    
    
}
