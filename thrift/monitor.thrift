namespace php Xin.Thrift.MonitorService

include "github/commits.thrift"
include "github/user.thrift"

include "baidu/tieba.thrift"

service Github {
    // 触发消息队列 收到的公共事件订阅信息
    bool receivedEvents(1:string username, 2:string token)

    // 触发消息队列 查看当前的commits数
    bool commits(1:string committer, 2:string token)

    // 更新某人的粉丝列表
    bool updateFollowers(1:string username, 2:string token)

    // 更新某人的关注列表
    bool updateFollowing(1:string username, 2:string token)

    // 触发消息 查看某人关注列表今日的commits数
    bool followingCommits(1:string username, 2:string token)

    // 显示时间段内，commits变化
    list<commits.CommitsLog> commitsLog(1:string committer, 2:i32 btime, 3:i32 etime)

    // 从Github查询用户信息
    user.UserProfile userProfile(1:string username, 2:string token)
}

service Baidu {
    // 贴吧签到
    bool tiebaSign(1:string bdUss, 2:string nickName)

    // 获取我的最新贴吧列表
    list<tieba.BaiduTieba> myTiebas(1:string bdUss, 2:string nickName)

    // 获取我的某个贴吧
    tieba.BaiduTieba tieba(1:string bdUss, 2:string nickName, 3:string name)

    // 获取DB中的贴吧列表
    list<tieba.BaiduTieba> tiebaList(1: string nickName)
}