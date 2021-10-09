<?php
/**
 * tangBing
 * elasticSearch封装类
 */
namespace Library;
use Elasticsearch\ClientBuilder;

class ElasticsearchService
{
    /**
     * @var object
     */
    public object $api;

    /**
     * @var string
     */
    public string $boolTypeDefault = 'must';

    /**
     * @var string
     */
    public string $matchDefault = 'match';

    /**
     * Es2Service constructor.
     * @throws Errors
     */
    public function __construct()
    {

        $this->api = ClientBuilder::create()->setHosts(['127.0.0.1:9200'])->build();
    }

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        static $obj;
        if(!$obj){
            $obj =  new ElasticsearchService;
        }
        return $obj;
    }

    /**
     * 初始化索引参数
     * @param string $index_name
     * @param string $index_type
     * @return array
     * @throws Exception
     */
    protected function initEs(string $index_name, string $index_type = ''): array
    {
        if (empty($index_name)) {
            throw new \Exception('索引不能为空');
        }
        //索引参数--类似mysql数据库
        $initParams['index'] = $index_name;
        if (!empty($index_type)) {
            //类别--类似mysql表
            $initParams['type'] = $index_type;
        }
        return $initParams;
    }

    /**
     * filter速度快，不分词    ；  must自带分词
     * term精确查询不分词， match分词匹配
     * @param string $boolTypeDefault
     * @param string $matchDefault
     * @return bool
     * @throws Exception
     */
    public function setType(string $boolTypeDefault = 'must', string $matchDefault = 'match'): bool
    {
        if (in_array($boolTypeDefault, ['must', 'filter'])) {
            $this->boolTypeDefault = $boolTypeDefault;
        }
        if (in_array($matchDefault, ['term', 'match'])) {
            $this->matchDefault = $matchDefault;
        }
        return true;
    }

    /**
     * 创建一个索引
     * $mappings['field'] = ['字段名称' => ['type' => 'long']];
     * $mappings['_default_'] = ['_source' => ['enabled' => 'true']];
     * $settings --- ['index'=>['max_result_window'=>10000]]  settings是修改分片和副本数的。
     * @param string $index_name
     * @param $settings
     * @param array $mappings
     * @return array
     * @throws Exception
     */
    public function createIndex(string $index_name, array $settings = [], array $mappings = []): array
    {
        $initParams = $this->initEs($index_name);
        !empty($settings) && $initParams['body']['settings'] = $settings;
        if ($mappings['_default_'] ?? []) {
            $initParams['body']['mappings']['_default_'] = $mappings['_default_'];
        }
        if ($mappings['field'] ?? []) {
            $initParams['body']['mappings']['properties'] = $mappings['field'];
        }
        return $this->api->indices()->create($initParams);
    }

    /**
     * 更新索引的映射 mapping
     * @param array $data :　mappings是修改字段和类型的。
     * @param string $index_name
     * @return array
     * @throws Exception
     */
    public function setMapping(string $index_name, array $data): array
    {
        $initParams = $this->initEs($index_name);
        if ($data['field'] ?? []) {
            $initParams['body']['properties'] = $data['field'];
        }
        return $this->api->indices()->putMapping($initParams);
    }

    /**
     * 获取索引映射 mapping
     * @param string $index_name
     * @return array
     * @throws Exception
     */
    public function getMapping(string $index_name): array
    {
        $initParams = $this->initEs($index_name);
        return $this->api->indices()->getMapping($initParams);
    }

    /**
     * 向索引中插入数据
     * @param $data :一维数组,一条数据
     * @param string $index_name
     * @param string $id :唯一id
     * @return bool
     * @throws Exception
     */
    public function add(string $index_name, array $data, string $id = ''): bool
    {
        $initParams = $this->initEs($index_name);
        if (!empty($id)) {
            $initParams['id'] = $id;
        }
        $initParams['body'] = $data;
        $res = $this->api->index($initParams);
        if (!isset($res['_shards']['successful']) || !$res['_shards']['successful']) {
            return false;
        }
        return true;
    }

    /**
     *批量插入数据
     *$params['body'][]=array(
     *'create' => array('_id'=>$i), #注意create也可换成index
     *{ "delete": { "_index": "website", "_type": "blog", "_id": "123" }}
     *{ "create": { "_index": "website", "_type": "blog", "_id": "123" }}
     *{ "title": "My first blog post" }
     *{ "index": { "_index": "website", "_type": "blog" }}
     *{ "title": "My second blog post" }
     *{ "update": { "_index": "website", "_type": "blog", "_id": "123", "_retry_on_conflict" : 3} }
     *{ "doc" : {"title" : "My updated blog post"} }
     * @param $data :二维数组,多条数据
     * @param $fieldID
     * @param string $index_name
     * @return array
     * @throws \Exception
     */
    public function bulk(string $index_name, array $data, string $fieldID): array
    {
        if (empty($data)) {
            throw new \Exception('数据不能为空');
        }
        $initParams = $this->initEs($index_name);
        foreach ($data as $v) {
            if (!is_array($v)) {
                throw new \Exception('批量添加数据必须是二维数组');
            }
            if (!($v[$fieldID] ?? '')) {
                throw new \Exception('数据唯一id不能为空');
            }
            $initParams['body'][] = array(
                'index' => array(
                    #注意create:当文档不存在时创建;也可换成index:创建新文档或替换已有文档
                    '_id' => $v[$fieldID]
                ),
            );
            $initParams['body'][] = $v;
        }
        return $this->api->bulk($initParams);
    }

    /**
     * 检测文档Id是否存在
     * @param string $index_name
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function IdExists(string $index_name, int $id): bool
    {
        $initParams = $this->initEs($index_name);
        $initParams['id'] = $id;
        return $this->api->exists($initParams);
    }

    /**
     * 根据唯一id查询数据
     * @param string $index_name
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function searchById(string $index_name, int $id): array
    {
        $initParams = $this->initEs($index_name);
        $initParams['id'] = $id;
        return $this->api->get($initParams);
    }

    /**
     * 根据关键字查询数据
     * @param $where : 查询条件['name'='名称']
     * @param $data :分页、排序
     * @param $bodyCustom :自定义查询语句
     * @param string $index_name ：索引名称
     * @return array
     * @throws \Exception
     */
    public function search(string $index_name, array $where = [], array $data = [], array $bodyCustom = []): array
    {
        $this->setType('must','');
        $result = [];
        $params = $this->initEs($index_name);
        $params = $params + $this->otherData($data);
        if (empty($bodyCustom)) {
            $body = $this->setBody($where);
        } else {
            $body = $bodyCustom;//自定义查询语句
        }
        if (!empty($body)) $params['body']['query'] = $body;
        $params['body']['aggs'] = ['total_size' => ['cardinality' => ['field' => '_id']]];//总记录
        $res = $this->api->search($params);
        if (isset($res['_shards']['successful'])) {
            $result['size'] = $params['size'];
            $result['from'] = $params['from'];
            $result['total'] = $res['aggregations']['total_size']['value'] ?? 0;
            $result['scroll_id'] = $res['_scroll_id'] ?? '';
            $result['data'] = array_column($res['hits']['hits'], '_source', '_id');
        }
        return $result;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function otherData(array $data = []): array
    {
        $params['size'] = 20; //每页条数
        $params['from'] = 0;
        if ($data['fields'] ?? []) $params['_source'] = $data['fields'];
        if ($data['page_size'] ?? []) $params['size'] = $data['page_size'];
        if ($data['scroll'] ?? []) {
            $params['scroll'] = $data['scroll'];//深度（滚动）分页--scroll=5m ,5分钟内有效
        } else {
            if ($data['page'] ?? 0) {
                $page = (int)$data['page'];
                $pageNew = ($page - 1) > 0 ? ($page - 1) : 0;
                $pageFrom = $pageNew * $params['size'];
                $params['from'] = $pageFrom > 0 ? $pageFrom : 0;
            }
        }
        //排序
        if ($data['sort_field'] ?? []) {
            $sort_field = $data['sort_field'];
            $sort_rule = ($data['sort_rule'] ?? '') ? strtolower($data['sort_rule']) : 'desc';
            if (!is_array($sort_field)) {
                $params['body']['sort'][] = [
                    $sort_field => [
                        'order' => $sort_rule,
                    ]
                ];
            } else {
                foreach ($sort_field as $ke => $v) {
                    if (!empty($v)) {
                        $sort_rule = strtolower($v);
                    }
                    $params['body']['sort'][] = [
                        $ke => [
                            'order' => $sort_rule,
                        ]
                    ];
                }
            }
        }
        return $params;
    }

    /**
     * 条件组合
     * @param $where
     * @return array
     */
    public function setBody($where): array
    {
        $body = [];
        if (empty($where)) return $body;
        foreach ($where as $kk => $vk) {
            if (!is_array($vk)) {
                $body['bool'][$this->boolTypeDefault][]['term'] = [$kk => $vk];
                continue;
            }
            //多条件格式：$vk=['key','','']
            //must里面都必须满足，should里面条件至少满足一个就可以，filter指不分词查询
            //match在匹配时会对所查找的关键词进行分词，然后按分词匹配查找，而term会直接对关键词进行查找。
            //一般模糊查找的时候，多用match，而精确查找时可以使用term。
            $k = $vk[0] ?? '';//查询字段
            $type = $vk[1] ?? '';//查询方式
            $v = $vk[2] ?? '';//查询值
            $boolType = $vk[3] ?? $this->boolTypeDefault;//查询值
            $matchType = $vk[4] ?? $this->matchDefault;//查询值
            if (empty($k) || empty($type) || empty($v)) {
                continue;
            }
            switch ($type) {
                case 'between'://格式$v=[6,88]
                    if (($v[0] ?? 0) && ($v[1] ?? 0)) {
                        $body['bool'][($vk[3] ?? 'filter')][]['range'][$k] = ['gt' => $v[0], 'lt' => $v[1]];
                    }
                    break;
                case '>':
                    $body['bool'][$boolType][]['range'][$k]['gt'] = $v;
                    break;
                case '>=':
                    $body['bool'][$boolType][]['range'][$k]['gte'] = $v;
                    break;
                case '<':
                    $body['bool'][$boolType][]['range'][$k]['lt'] = $v;
                    break;
                case '<=':
                    $body['bool'][$boolType][]['range'][$k]['lte'] = $v;
                    break;
                case '='://切词查询
                    $body['bool'][$boolType][][$matchType][$k] = ['query' => $v, 'analyzer' => 'ik_smart'];
                    //$body['bool']['should'][]['match'][$k] = $v;
                    break;
                case '=='://通配符进行查询,“title”=>"*学校*"
                    $body['bool'][$boolType][]['wildcard'][$k] = $v;
                    break;
                case '!=':
                    $body['bool']['must_not'][][$matchType][$k] = $v;
                    break;
                case 'or':
                    $body['bool']['should'][] = array($matchType => [$k => $v]);
                    break;
                case 'and':
                    $body['bool'][$boolType][] = array($matchType => [$k => $v]);
                    break;
                case 'in'://格式$v=[6,88]
                    if (is_array($v)) {
                        $data_one = [];
                        foreach ($v as $n) {
                            if (!empty($n)) $data_one[] = array($matchType => array($k => $n));
                        }
                        $body['bool'][$vk[3] ?? 'filter'][]['bool']['should'] = $data_one;
                    }
                    break;
                case 'not in'://格式$v=[6,88],term
                    if (is_array($v)) {
                        foreach ($v as $n) {
                            if (!empty($n)) $body['bool']['filter']['bool']['must_not'][] = array($matchType => array($k => $n));
                        }
                    }
                    break;
                case 'like'://格式$v=['学校',100]
                    if (!is_array($v)) {
                        $v = [$v, '100%'];
                    }
                    $body['bool'][$boolType][][$matchType][$k] = ['query' => $v[0], 'minimum_should_match' => $v[1]];//相似度，匹配度
                    break;
                default:
            }
        }
        if (isset($body['bool']['should']) && isset($body['bool']['must'])) {
            $body['bool']['minimum_should_match'] = 1;
        }
        return $body;
    }

    /**
     * 查询索引是否存在
     * @param string $index_name
     * @return bool
     */
    public function exist(string $index_name): bool
    {
        $params['index'] = $index_name;
        return $this->api->indices()->exists($params);
    }


    /**
     * 根据唯一id删除
     * @param string $index_name
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function delete(string $index_name, int $id): bool
    {
        $params = $this->initEs($index_name);
        $params['id'] = $id;
        $res = $this->api->delete($params);
        if (!isset($res['_shards']['successful'])) {
            return false;
        }
        return true;
    }

    /**
     * @param string $index_name
     * @param array $data
     * @param array $where
     * @param array $bodyCustom
     * @return array
     * @throws Exception
     */
    protected function whereAgs(string $index_name, array $data, array $where = [], array $bodyCustom = []): array
    {
        if (empty($data)) {
            return [];
        }
        $params = $this->initEs($index_name);
        $params['size'] = 10;
        /**
         * 条件组合过滤，筛选条件
         */
        if ($where) {
            if (empty($bodyCustom)) {
                $bodyCustom = $this->setBody($where);
            }
            if (!empty($bodyCustom)) {
                $params['body']['query'] = $bodyCustom;
            }
        }
        //分组、排序设置
        if ($data['agg'] ?? []) {
            //字段值
            $agg = [];
            if ($data['agg']['terms'] ?? []) {
                $agg['_result']['terms'] = ['field' => $data['agg']['terms'], 'size' => 500];
                if ($data['agg'] ?? []) {
                    foreach ($data['agg']['order'] as $key => $val) {
                        $fields = 'result.' . $key;
                        $agg['_result']['terms']['order'] = [
                            $fields => $val
                        ];
                        unset($fields);
                    }
                }
            }
            //统计
            if ($data['agg']['field'] ?? []) {
                $agg['_result']['aggs'] = [
                    'result' => [
                        'extended_stats' => [
                            'field' => $data['agg']['field']
                        ]
                    ]
                ];
            }
            if ($data['agg']['field_total'] ?? []) {
                $agg['total_size']['cardinality'] = [
                    'field' => $data['agg']['field_total']
                ];
            }
            //日期聚合统计
            if ($data['agg']['date'] ?? []) {
                $date_agg = $data['agg']['date'];
                //根据日期分组
                if ($date_agg['field'] ?? []) {
                    $agg['result'] = [
                        'date_histogram' => [
                            'field' => $data['agg']['date']['field'],
                            'interval' => '2h',
                            'format' => 'yyyy-MM-dd  HH:mm:ss'
                        ]
                    ];
                }
                if ($date_agg['agg'] ?? []) {
                    //分组
                    if ($date_agg['agg']['terms'] ?? []) {
                        $agg['result']['aggs']['result']['terms'] = [
                            'field' => $date_agg['agg']['terms'],
                            'size' => 100,
                        ];
                    }
                    //统计最大、最小值等
                    if ($date_agg['agg']['stats'] ?? []) {
                        $agg['result']['aggs']['result']['aggs'] = [
                            'result_stats' => [
                                'extended_stats' => [
                                    'field' => $date_agg['agg']['stats']
                                ]
                            ]
                        ];
                    }
                }
            }
            if ($agg) $params['body']['aggs'] = $agg;
        }
        return $params;
    }

    /**
     * 根据条件获取统计数量
     * @param string $index_name
     * @param array $data
     * @param array $bodyCustom
     * @return int
     * @throws Exception
     */
    public function getTotals(string $index_name, array $data, array $bodyCustom = []): int
    {
        $this->setType('filter','');
        $condition = $data['condition'] ?? [];
        if(!isset($data['agg']['field_total'])){
            $data['agg']['field_total'] = '_id';
        }
        $params = $this->whereAgs($index_name, $data, $condition, $bodyCustom);
        $params['size'] = 1; //每页条数
        $params['from'] = 0;
        $res = $this->api->search($params);
        if (!isset($res['_shards']['successful'])) {
            return 0;
        } else {
            return $res['aggregations']['total_size']['value'] ?? 0;
        }
    }

    /**
     * 批量查询，只能根据id来查
     * @param string $index_name
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function mGet(string $index_name, array $data): array
    {
        if (empty($data)) return [];
        $params = $this->initEs($index_name);
        if ($data['fields'] ?? []) {
            $query['ids'] = $data['fields'];
            $params['body'] = $query;
        }
        return $this->api->mget($params);
    }

    /**
     * 深度分页
     * @param $scroll_id
     * @return array
     * @throws \Exception
     */
    public function scroll($scroll_id): array
    {
        $params = [
            'scroll_id' => $scroll_id,
            'scroll' => '5m'
        ];
        $res = $this->api->scroll($params);
        if (isset($res['_scroll_id']) && $res['_scroll_id'] != $scroll_id) {
            $this->api->clearScroll(['scroll_id' => $scroll_id]);
        }
        if (!isset($res['_shards']['successful'])) {
            return [];
        } else {
            $result['total'] = is_array($res['hits']['total']) ? $res['hits']['total']['value'] : $res['hits']['total'];
            $result['scroll_id'] = isset($res['_scroll_id']) ? $res['_scroll_id'] : '';
            $result['data'] = array_column($res['hits']['hits'], '_source', '_id');
        }
        return $result;
    }

    /**
     * 删除索引
     * @param int $del :删除索引==999 ,防止误删除索引
     * @param string $index_name
     * @return bool
     */
    public function delIndex(string $index_name, int $del): bool
    {
        if ($del === 999) {
            $indexData = $this->exist($index_name);
            if ($indexData) {
                $result = $this->api->indices()->delete(['index' => $index_name]);
                if ($result['acknowledged']) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * 检查es是否正常连接
     * @return bool
     * @throws \Exception
     */
    public function test(): bool
    {
        return $this->api->ping();
    }
}