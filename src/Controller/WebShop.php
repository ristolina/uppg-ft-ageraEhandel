<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Helper\ageraEhandelHelper;
use Symfony\Component\HttpFoundation\Request;

class WebShop extends AbstractController
{
    private ageraEhandelHelper $ageraEhandel;
    private $products = null;

    public function __construct(ageraEhandelHelper $ageraEhandel)
    {
        $this->ageraEhandel = $ageraEhandel;
    }

    #[Route('/webshop/show')]
    public function showData(Request $request)
    {
        $sortOrder = $request->request->get('sort');

        if ($this->products == null) {
            $this->products = $this->ageraEhandel->fetchData();
            $this->products = $this->SortArray($sortOrder);
            $categories = $this->returnCategories();
        }


        return $this->render('base.html.twig', [
            'products' => $this->products,
            'categories' => $categories

        ]);
    }

    /*
    * Sort array based on SortOrder, returns sorted array.
    */
    private function SortArray($sortOrder) : array
    {
        $arrayToSort = $this->products;

        if ($sortOrder == 'alfabetiskt') {
            $names = array_column($arrayToSort, 'artiklar_benamning');
            array_multisort($names, SORT_ASC, $arrayToSort);
            //$arrayToSort = [$names, $arrayToSort];

        } elseif ($sortOrder == 'pris-stigande') {
            $prices = array_column($arrayToSort, 'pris');
            array_multisort($prices, SORT_ASC, $arrayToSort);
            //$arrayToSort = [$prices, $arrayToSort];
        } elseif ($sortOrder == 'pris-fallande') {
            $prices = array_column($arrayToSort, 'pris');
            array_multisort($prices, SORT_DESC, $arrayToSort);
            //$arrayToSort = [$prices, $arrayToSort];
        }
        return $arrayToSort;
    }

    /*
    * Returns array of unique values of key 'artikelkategorier_id'
    */
    private function returnCategories() : array
    {
        return array_unique(array_column($this->products, 'artikelkategorier_id'));
    }
}
