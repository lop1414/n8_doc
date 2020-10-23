<?php

namespace App\Http\Controllers\Admin;

use App\Common\Controllers\Admin\AdminController;
use App\Common\Enums\StatusEnum;
use App\Common\Enums\SystemAliasEnum;
use App\Common\Services\CurdService;
use App\Common\Tools\CustomException;
use App\Common\Traits\BuildTree;
use App\Models\ArticleContentModel;
use App\Models\ArticleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends AdminController
{
    use BuildTree;

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->model = new ArticleModel();

        parent::__construct();
    }

    /**
     * 详情预处理
     */
    public function readPrepare(){
        $this->curdService->findAfter(function(){
            // 关联文章内容
            $this->curdService->findData->article_content;
        });
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws CustomException
     * 创建
     */
    public function create(Request $request){
        try{
            $requestData = $request->all();

            $this->curdService->setRequestData($requestData);

            // 事务开启
            DB::beginTransaction();

            $this->curdService->addField('name')->addValidRule('required');
            $this->curdService->addField('system_alias')->addValidRule('required')
                ->addValidEnum(SystemAliasEnum::class);
            $this->curdService->addField('status')->addDefaultValue(StatusEnum::ENABLE)
                ->addValidEnum(StatusEnum::class);
            $this->curdService->addField('parent_id')->addValidRule('integer');
            $this->curdService->addField('content')->addValidRule('required');
            $this->curdService->addField('order')->addDefaultValue(99);

            $ret = $this->curdService->create();
            if(!$ret){
                throw new CustomException([
                    'code' => 'CREATE_ARTICLE_FAIL',
                    'message' => '创建文章失败',
                    'log' => true,
                    'data' => $requestData,
                ]);
            }

            $contentService = new CurdService(new ArticleContentModel());

            // 追加字段
            $contentService->addColumns([$contentService->getModel()->getPrimaryKey()]);
            $contentData = [
                'content' => $requestData['content'],
                'article_id' => $this->curdService->getModel()->id,
            ];

            $contentService->setRequestData($contentData);

            $ret = $contentService->create();
            if(!$ret){
                throw new CustomException([
                    'code' => 'CREATE_ARTICLE_CONTENT_FAIL',
                    'message' => '创建文章内容失败',
                    'log' => true,
                    'data' => $contentData,
                ]);
            }

            // 事务提交
            DB::commit();

            // 关联内容
            $this->curdService->getModel()->article_content;

            return $this->ret($ret, $this->curdService->getModel());
        }catch(CustomException $e){
            // 事务回滚
            DB::rollBack();
            throw new CustomException($e->getErrorInfo(true));
        }catch(\Exception $e){
            // 事务回滚
            DB::rollBack();
            throw new \Exception($e->getCode(), $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws CustomException
     * 更新
     */
    public function update(Request $request){
        try{
            $requestData = $request->all();

            $this->curdService->setRequestData($requestData);

            // 事务开启
            DB::beginTransaction();

            $this->curdService->addField('name')->addValidRule('required');
            $this->curdService->addField('system_alias')->addValidRule('required')
                ->addValidEnum(SystemAliasEnum::class);
            $this->curdService->addField('status')->addValidEnum(StatusEnum::class);
            $this->curdService->addField('parent_id')->addValidRule('integer');
            $this->curdService->addField('content')->addValidRule('required');

            $ret = $this->curdService->update();
            if(!$ret){
                throw new CustomException([
                    'code' => 'UPDATE_ARTICLE_FAIL',
                    'message' => '更新文章失败',
                    'log' => true,
                    'data' => $requestData,
                ]);
            }

            $contentService = new CurdService(new ArticleContentModel());

            $contentData = [
                'content' => $requestData['content'],
                'article_id' => $this->curdService->getModel()->id,
            ];

            $contentService->setRequestData($contentData);

            $ret = $contentService->update();
            if(!$ret){
                throw new CustomException([
                    'code' => 'UPDATE_ARTICLE_CONTENT_FAIL',
                    'message' => '更新文章内容失败',
                    'log' => true,
                    'data' => $contentData,
                ]);
            }

            // 事务提交
            DB::commit();

            // 关联内容
            $this->curdService->getModel()->article_content;

            return $this->ret($ret, $this->curdService->getModel());
        }catch(CustomException $e){
            // 事务回滚
            DB::rollBack();
            throw new CustomException($e->getErrorInfo(true));
        }catch(\Exception $e){
            // 事务回滚
            DB::rollBack();
            throw new \Exception($e->getCode(), $e->getMessage());
        }
    }
}
