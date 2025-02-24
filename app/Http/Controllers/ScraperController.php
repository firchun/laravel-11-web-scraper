<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ScraperController extends Controller
{
    public function scrape(Request $request)
    {
        $request->validate([
            'url' => 'required|url'
        ]);

        $client = HttpClient::create();
        $response = $client->request('GET', $request->url);

        if ($response->getStatusCode() !== 200) {
            return response()->json(['error' => 'Gagal mengambil data'], 500);
        }

        $html = $response->getContent();
        $crawler = new Crawler($html);

        $data = [];

        // Ambil semua link dari halaman
        $crawler->filter('a')->each(function (Crawler $node) use (&$data) {
            $data[] = [
                'text' => trim($node->text()),
                'href' => $node->attr('href')
            ];
        });

        return response()->json($data);
    }

    // ✅ Fungsi baru untuk download CSV
    public function downloadCSV(Request $request)
    {
        $data = json_decode($request->input('data'), true);

        $response = new StreamedResponse(function () use ($data) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Text', 'Link']); // Header CSV

            foreach ($data as $row) {
                fputcsv($handle, [$row['text'], $row['href']]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="scraped_data.csv"');

        return $response;
    }
}