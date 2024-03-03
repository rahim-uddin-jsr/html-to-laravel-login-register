<?php

namespace App\Http\Controllers;

use App\Portfolio;
use App\PortfolioImage;
use App\Product;
use App\ProductFeatures;
use App\SectionDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

function checkIsNull($item)
{
    if ($item) {
        return 'on';
    } else {
        return "off";
    }
}
class BackendController extends Controller
{
    public function updatePricing(Request $request)
    {
        // dd($request->all());
        $pricingDescription = SectionDescription::find($request->section_id);
        if ($pricingDescription) {
            # code...
            $pricingDescription->update([
                'description' => $request->section_description,
            ]);

            $totalFeaturesCount = ProductFeatures::all();
            foreach ($totalFeaturesCount as $key => $value) {
                $featureIndex = $key + 1;
                $products_feature = ProductFeatures::find($value->id);
                $featureIndex = "feature$key";
                $basicIndex = "basic$key";
                $businessIndex = "business$key";
                $developerIndex = "developer$key";
                $products_feature->update([
                    'feature_name' => $request->$featureIndex,
                    'isBasic' => checkIsNull($request->$basicIndex),
                    'business' => checkIsNull($request->$businessIndex),
                    'developer' => checkIsNull($request->$developerIndex),
                ]);
            }
            // for ($i = 0; $i < $totalFeaturesCount; $i++) {
            //     $featureIndex = $i + 1;
            //     $products_feature = ProductFeatures::find($featureIndex);
            //     $featureIndex = "feature$i";
            //     $basicIndex = "basic$i";
            //     $businessIndex = "business$i";
            //     $developerIndex = "developer$i";
            //     $products_feature->update([
            //         'feature_name' => $request->$featureIndex,
            //         'isBasic' => checkIsNull($request->$basicIndex),
            //         'business' => checkIsNull($request->$businessIndex),
            //         'developer' => checkIsNull($request->$developerIndex),
            //     ]);
            // }

            $product1 = Product::find(1);
            $product2 = Product::find(2);
            $product3 = Product::find(3);
            // dd($product1);
            $product1->update([
                'price' => $request->free_plan_price,
            ]);
            $product2->update([
                'price' => $request->business_plan_price,
            ]);
            $product3->update([
                'price' => $request->developer_plan_price,
            ]);
        } else {
            dd($request->all());
        }
        return back();

    }

    // public function deleteFeature($id) {
    //     // dd($id);
    //     ProductFeatures::find($id)->delete();
    //     return back();
    // }
    public function deleteFeature1($id)
    {
        // dd($id);
        ProductFeatures::find($id)->delete();
        return back();
    }
    public function addFeature(Request $request)
    {
        ProductFeatures::create([
            'feature_name' => $request->feature_name,
            'isBasic' => checkIsNull($request->isBasic),
            'business' => checkIsNull($request->isBusiness),
            'developer' => checkIsNull($request->isDeveloper),
        ]);
        // dd($request->all());
        // ProductFeatures::find($id)->delete();
        return back();
    }

    public function updateDescription(Request $request)
    {
        $description = SectionDescription::find($request->id);
        $description->update([
            'description' => $request->section_description,
        ]);
        return back();
    }
    public function deletePortfolio($id)
    {
        $portfolio = Portfolio::find($id);
        $portfolio->delete();
        $destination = 'assets/img/home/portfolio/' . $portfolio->image_url;
        if (File::exists($destination)) {
            File::delete($destination);
        }
        return back();
    }
    public function deletePortfolioSingleImage($id)
    {
        $portfolio = PortfolioImage::find($id);
        // dd($portfolio);
        $portfolio->delete();
        $destination = 'assets/img/home/portfolio/' . $portfolio->image_url;
        if (File::exists($destination)) {
            File::delete($destination);
        }
        return back();
    }
    public function updatePortfolioSingleImage(Request $request, $id)
    {
        dd($id);
        dd($request->all());
        $portfolioUpdateImage = PortfolioImage::find($id);
        if ($request->hasfile('update_img')) {
            $file = $request->file('update_img');
            $extension = $file->getClientOriginalExtension();
            $name = $file->getClientOriginalName();
            // dd($extension,$name);
            $destination = 'assets/img/home/portfolio/' . $portfolioUpdateImage->image_url;
            $filename = uniqid() . '.' . $extension;
            $file->move('assets/img/home/portfolio', $filename);
            $portfolioUpdateImage->update([
                'image_url' => $filename,

            ]);
            if (File::exists($destination)) {
                File::delete($destination);
            }

        }
        return back();
    }

    public function updatePortfolio(Request $request, $id)
    {
        $portfolioUpdate = Portfolio::find($id);
        if ($request->hasfile('update_img')) {
            $file = $request->file('update_img');
            $extension = $file->getClientOriginalExtension();
            $name = $file->getClientOriginalName();
            // dd($extension,$name);
            $destination = 'assets/img/home/portfolio/' . $portfolioUpdate->image_url;
            $filename = uniqid() . '.' . $extension;
            $file->move('assets/img/home/portfolio', $filename);
            $portfolioUpdate->update([
                'image_url' => $filename,

            ]);
            if (File::exists($destination)) {
                File::delete($destination);
            }

        }
        $portfolioUpdate->update([
            'category'=>$request->category,
            'name'=>$request->name,
        ]);
        return back();
    }

    public function addPortfolio(Request $request) {
        // dd($request->all());
        if ($request->hasfile('update_img')) {
            $files = $request->file('update_img');
            $lastId= Portfolio::insertGetId([
                 // 'image_url' => $filename,
                 'name' => $request->name,
                 'category' => $request->category,
                 'image_url' => $request->image_url,
                 'project_title' => $request->project_title,
                 'project_description' => $request->project_description,
                 'client' => $request->client,
                 'project_date' => $request->project_date,
                 'project_url' => $request->project_url,

             ]);
            foreach ($files as $key => $file) {

            $extension = $file->getClientOriginalExtension();
            // $name = $file->getClientOriginalName();
            $filename = uniqid() . '.' . $extension;
            $file->move('assets/img/home/portfolio', $filename);
            PortfolioImage::create([
                'portfolio_id'=>$lastId,
                'image_url' => $filename,
            ]);
        }
        }
        return back();
    }
}
