<?php


namespace App\Core\Dashboard\Service;

use App\Core\Dashboard\Repository\Article\ArticleRepository;
use App\Core\Dashboard\Repository\ArticleCategorie\ArticleCategorieRepository;
use App\Core\Dashboard\Repository\ArticleSubCategorie\ArticleSubCategorieRepository;
use Illuminate\Support\Facades\Log;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SiteMapService
{



    public $articleCategorieRepository;

    public $articleSubCategorieRepository;
    public $articleRepository;
    public $sitemap;

    public function __construct(
        ArticleCategorieRepository $articleCategorieRepository,
        ArticleSubCategorieRepository $articleSubCategorieRepository,
        ArticleRepository $articleRepository
    ) {

        $this->sitemap = Sitemap::create();


        $this->articleCategorieRepository = $articleCategorieRepository;
        $this->articleSubCategorieRepository = $articleSubCategorieRepository;
        $this->articleRepository = $articleRepository;
    }

    public function generation()
    {
   

        try {


            $this->ensurePublicWritable();

            $sitemap = $this->sitemap;
            $perPage = config('sitemap_settings.per_page', 10);

            // 📌 Static Pages
            $this->createUrl($sitemap); // Home Page
            $this->createUrl($sitemap, "articles/categories", 0.9); // Categories Page
            $this->createUrl($sitemap, "contact", 0.8); // Contact Page
            $this->createUrl($sitemap, "about", 0.8); // About Page

            // 📌 Dynamic Pages

            // 1️⃣ Article Subcategories Pagination

            $this->generatePaginatedUrls(
                $sitemap,
                $this->articleCategorieRepository->getIds(),
                fn($id) => $this->articleCategorieRepository->getSubCategoriesCountById($id),
                "articles/subCategories",
                $perPage
            );

            // 2️⃣ Articles Listing by Subcategory

            $this->generatePaginatedUrls(
                $sitemap,
                $this->articleSubCategorieRepository->getIds(),
                fn($id) => $this->articleRepository->getCountBySubCategorieId($id),
                "articles/listing",
                $perPage
            );

            // 3️⃣ Single Article Pages
            foreach ($this->articleRepository->getIds() as $article) {
                $this->createUrl($sitemap, "articles/singlePage/?id={$article->id}", 0.9);
            }

            // 🔹 Save the Sitemap file
            $path = public_path('sitemap.xml');

            $sitemap->writeToFile($path);

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to write sitemap file: " . $e->getMessage());
            return false; // Return false to indicate failure

        }
    }

    private function ensurePublicWritable()
    {
        $sitemapPath = public_path('sitemap.xml');

        if (!file_exists($sitemapPath)) {
            file_put_contents($sitemapPath, '');
            chmod($sitemapPath, 0666);
        } elseif (!is_writable($sitemapPath)) {
            chmod($sitemapPath, 0666);
        }
    }



    private function createUrl($sitemap, $url = "", $priority = 1.0)
    {

        $paseUrl = config('sitemap_settings.pase_url');

        $sitemap->add(Url::create("{$paseUrl}/{$url}")
            ->setLastModificationDate(now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority($priority));
    }

    private function generatePaginatedUrls($sitemap, $ids,  callable $getTotalItemsCallback, $url, $perPage)
    {

        foreach ($ids as $row) {

            $totalItems = $getTotalItemsCallback($row->id);
            // loop pages
            $totalPages = max(1, ceil($totalItems / $perPage));

            for ($page = 1; $page <= $totalPages; $page++) {

                $pageUrl  = "{$url}/?id={$row->id}&page={$page}&perPage={$perPage}";

                $this->createUrl($sitemap, $pageUrl, 0.8);
            }
        }
    }
}
