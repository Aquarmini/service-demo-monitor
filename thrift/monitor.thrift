namespace php Xin.Thrift.MonitorService

include "github/commits.thrift"
include "github/user.thrift"

include "baidu/tieba.thrift"

service Github {
    // 触发消息队列 收到的公共事件订阅信息
    bool receivedEvents(string username, string token)

    // 触发消息队列 查看当前的commits数
    bool commits(string committer, string token)

    // 更新某人的粉丝列表
    bool updateFollowers(string username, string token)

    // 更新某人的关注列表
    bool updateFollowing(string username, string token)

    // 触发消息 查看某人关注列表今日的commits数
    bool followingCommits(string username, string token)

    // 显示时间段内，commits变化
    list<commits.CommitsLog> commitsLog(string committer, i32 btime, i32 etime)

    // 从Github查询用户信息
    user.UserProfile userProfile(string username, string token)
}

service Baidu {
    // 贴吧签到
    bool tiebaSign(string bdUss, string nickName)

    // 获取我的贴吧列表
    list<tieba.BaiduTieba> myTiebas(string bdUss, string nickName)

    // 获取我的某个贴吧
    tieba.BaiduTieba tieba(string bdUss, string nickName, string name)
}