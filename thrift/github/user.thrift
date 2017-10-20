namespace php Xin.Thrift.MonitorService

struct UserProfile {
    1: string   login,
    2: i64      id,
    3: string   avatar_url,
    4: string   html_url,
    5: string   type,
    6: string   name,
    7: string   company,
    8: string   blog,
    9: string   location,
    10: string  email,
    11: string  bio,
    12: i32     public_repos,
    13: i32     public_gists,
    14: i32     followers,
    15: i32     following
}