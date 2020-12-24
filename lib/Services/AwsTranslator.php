<?php

use Aws\Translate\TranslateClient;
use Aws\Exception\AwsException;
use Aws\Credentials\Credentials;

class AwsTranslator
{
    private $client;

    public function __construct($env)
    {
        $credentials = new Credentials($env['AWS_KEY'], $env['AWS_SECRET']);

        /**
         * This code expects that you have AWS credentials set up per:
         * https://docs.aws.amazon.com/sdk-for-php/v3/developer-guide/guide_credentials.html
         */
        //Create a Translate Client
        $this->client = new TranslateClient([
            'region' => $env['AWS_REGION'],
            'version' => $env['AWS_VERSION'],
            'credentials' => $credentials
        ]);
    }


    /**
     * Translate the given text 
     * @param string $currentLanguage
     * @param string $targetLanguage
     * @param string $textToTranslate
     */
    public function translate(string $currentLanguage, string $targetLanguage, string $textToTranslate)
    {
        $response = [
            "error" => "",
            "text" => "",
            "code" => 200
        ];
        try {
            $result = $this->client->translateText([
                'SourceLanguageCode' => $currentLanguage,
                'TargetLanguageCode' => $targetLanguage,
                'Text' => $textToTranslate,
            ]);

            if (isset($result["TranslatedText"])) {
                $response["text"] = $result["TranslatedText"];
            } else {
                $response["error"] = "Error on transalte, error response from AWS";
                $response["code"] = 400;
            }
        } catch (AwsException $e) {
            $response["error"]  = $e->getMessage();
            $response["code"] = 400;
        }

        return $response;
    }
}
