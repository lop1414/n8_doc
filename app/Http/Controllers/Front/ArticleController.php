<?php

namespace App\Http\Controllers\Front;

use App\Common\Controllers\Front\FrontController;
use App\Common\Enums\SystemAliasEnum;
use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Common\Traits\BuildTree;
use App\Models\ArticleModel;
use Illuminate\Http\Request;

class ArticleController extends FrontController
{
    use BuildTree;

    /**
     * constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \App\Common\Tools\CustomException
     * 列表
     */
    public function select(Request $request){
        $data = $request->all();

        $this->validRule($data, [
            'system_alias' => 'required',
        ]);

        Functions::hasEnum(SystemAliasEnum::class, $data['system_alias']);

        $articleModel = new ArticleModel();
        $acticles = $articleModel->where('system_alias', $data['system_alias'])
            ->orderBy('order', 'desc')
            ->get();

        return $this->success($acticles);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \App\Common\Tools\CustomException
     * 树
     */
    public function tree(Request $request){
        $data = $request->all();

        $this->validRule($data, [
            'system_alias' => 'required',
        ]);

        Functions::hasEnum(SystemAliasEnum::class, $data['system_alias']);

        $articleModel = new ArticleModel();
        $acticles = $articleModel->where('system_alias', $data['system_alias'])
            ->orderBy('order', 'desc')
            ->get();

        $tree = $this->buildTree($acticles);

        return $this->success($tree);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \App\Common\Tools\CustomException
     * 详情
     */
    public function read(Request $request){
        $data = $request->all();

        $this->validRule($data, [
            'id' => 'required',
        ]);

        $articleModel = new ArticleModel();
        $article = $articleModel->find($data['id']);

        if(empty($article)){
            throw new CustomException([
                'code' => 'NOT_FOUND_TARGET',
                'message' => '找不到目标',
            ]);
        }

        // 关联文章内容
        $article->article_content;

        return $this->success($article);
    }
}
