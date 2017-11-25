namespace php Xin.Thrift.MonitorService

include "github/commits.thrift"
include "github/user.thrift"

include "baidu/tieba.thrift"

exception ThriftException {
  1: i32 code,
  2: string message
}

service Github {
    // 触发消息队列 收到的公共事件订阅信息
    bool receivedEvents(1:string username, 2:string token) throws (1:ThriftException ex)

    // 触发消息队列 查看当前的commits数
    bool commits(1:string committer, 2:string token) throws (1:ThriftException ex)

    // 更新某人的粉丝列表
    bool updateFollowers(1:string username, 2:string token) throws (1:ThriftException ex)

    // 更新某人的关注列表
    bool updateFollowing(1:string username, 2:string token) throws (1:ThriftException ex)

    // 触发消息 查看某人关注列表今日的commits数
    bool followingCommits(1:string username, 2:string token) throws (1:ThriftException ex)

    // 显示时间段内，commits变化
    list<commits.CommitsLog> commitsLog(1:string committer, 2:i32 btime, 3:i32 etime) throws (1:ThriftException ex)

    // 从Github查询用户信息
    user.UserProfile userProfile(1:string username, 2:string token) throws (1:ThriftException ex)
}

service Baidu {
    // 贴吧签到
    bool tiebaSign(1:string bdUss, 2:string nickName) throws (1:ThriftException ex)

    // 获取我的最新贴吧列表
    list<tieba.BaiduTieba> myTiebas(1:string bdUss, 2:string nickName) throws (1:ThriftException ex)

    // 获取我的某个贴吧
    tieba.BaiduTieba tieba(1:string bdUss, 2:string nickName, 3:string name) throws (1:ThriftException ex)

    // 获取DB中的贴吧列表
    list<tieba.BaiduTieba> tiebaList(1: string nickName) throws (1:ThriftException ex)
}